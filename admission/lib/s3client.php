<?php
/**
 * Minimal S3 (AWS Signature V4) client for the admission app.
 *
 * Just enough to PUT and GET objects against an S3-compatible
 * endpoint (ZevCloud Storage / Garage). No vendored SDK — keeps
 * the container small and avoids a Composer dependency for the
 * one operation we actually need.
 *
 * Configuration is read from env vars (set in the ZevCloud
 * service's Variables tab):
 *
 *   S3_ENDPOINT       e.g. https://storage.zevcloud.net
 *   S3_REGION         e.g. eu-central-1
 *   S3_BUCKET         e.g. zc-storage
 *   S3_ACCESS_KEY     issued when the bucket was created
 *   S3_SECRET_KEY     "
 *   S3_PREFIX         optional, default "admission" — keeps this
 *                     app's keys namespaced inside a shared bucket
 */

class S3Client {
    private string $endpoint;
    private string $region;
    private string $bucket;
    private string $accessKey;
    private string $secretKey;
    private string $prefix;

    public function __construct() {
        $this->endpoint  = rtrim((string)(getenv('S3_ENDPOINT') ?: ''), '/');
        $this->region    = (string)(getenv('S3_REGION') ?: 'us-east-1');
        $this->bucket    = (string)(getenv('S3_BUCKET') ?: '');
        // Accept both AWS-canonical names (which ZevCloud's storage
        // console injects via its Connect-to-a-service flow) and the
        // shorter S3_ACCESS_KEY / S3_SECRET_KEY pair, so the same
        // code works whether vars were injected automatically or
        // pasted in manually.
        $this->accessKey = (string)(getenv('S3_ACCESS_KEY_ID') ?: getenv('S3_ACCESS_KEY') ?: '');
        $this->secretKey = (string)(getenv('S3_SECRET_ACCESS_KEY') ?: getenv('S3_SECRET_KEY') ?: '');
        $this->prefix    = trim((string)(getenv('S3_PREFIX') ?: 'admission'), '/');
    }

    public function isConfigured(): bool {
        return $this->endpoint !== ''
            && $this->bucket   !== ''
            && $this->accessKey !== ''
            && $this->secretKey !== '';
    }

    /**
     * Build the full S3 key for a given relative path inside the
     * admission storage tree. Example:
     *   relativeKey("passport/FORM-ABC.jpg")
     *     => "admission/storage/passport/FORM-ABC.jpg"
     */
    public function relativeKey(string $path): string {
        $clean = ltrim($path, '/');
        return $this->prefix === ''
            ? "storage/{$clean}"
            : "{$this->prefix}/storage/{$clean}";
    }

    /**
     * Upload a local file to S3. Returns true on 2xx response.
     * Caller handles the boolean (mirrors move_uploaded_file's API
     * so the call sites stay symmetric).
     */
    public function putFile(string $localPath, string $relativeKey, string $contentType = 'application/octet-stream'): bool {
        if (!$this->isConfigured()) return false;
        if (!is_readable($localPath)) return false;

        $body = file_get_contents($localPath);
        if ($body === false) return false;

        $key = $this->relativeKey($relativeKey);
        [$status, ] = $this->signedRequest('PUT', $key, $body, $contentType);
        return $status >= 200 && $status < 300;
    }

    /**
     * Stream an S3 object to the current PHP response. Sets
     * Content-Type from S3's response headers and exits when done.
     * Used by the storage proxy.
     */
    public function streamObject(string $relativeKey): bool {
        if (!$this->isConfigured()) return false;
        $key = $this->relativeKey($relativeKey);
        [$status, $headers, $body] = $this->signedRequest('GET', $key, '', '', /* returnBody */ true);
        if ($status !== 200 || $body === null) return false;

        $ct = $headers['content-type'] ?? 'application/octet-stream';
        header('Content-Type: ' . $ct);
        header('Cache-Control: private, max-age=300');
        echo $body;
        return true;
    }

    /**
     * AWS Signature V4 signed request. Path-style addressing (works
     * against both AWS S3 and Garage/MinIO without virtual-host DNS).
     *
     * Returns [statusCode, lowercased-headers, body|null].
     */
    private function signedRequest(string $method, string $key, string $body = '', string $contentType = '', bool $returnBody = false): array {
        $now = gmdate('Ymd\THis\Z');
        $date = substr($now, 0, 8);
        $host = parse_url($this->endpoint, PHP_URL_HOST);
        $port = parse_url($this->endpoint, PHP_URL_PORT);
        $scheme = parse_url($this->endpoint, PHP_URL_SCHEME) ?: 'https';
        $hostHeader = $port ? "{$host}:{$port}" : $host;

        // Path-style URL: /<bucket>/<key>
        $encodedKey = implode('/', array_map('rawurlencode', explode('/', $key)));
        $path = "/{$this->bucket}/{$encodedKey}";
        $url = "{$scheme}://{$hostHeader}{$path}";

        $payloadHash = hash('sha256', $body);

        // Canonical request
        $canonicalHeaders = "host:{$hostHeader}\n"
                          . "x-amz-content-sha256:{$payloadHash}\n"
                          . "x-amz-date:{$now}\n";
        $signedHeaders = 'host;x-amz-content-sha256;x-amz-date';
        $canonicalRequest = "{$method}\n{$path}\n\n{$canonicalHeaders}\n{$signedHeaders}\n{$payloadHash}";

        // String to sign
        $algorithm = 'AWS4-HMAC-SHA256';
        $credentialScope = "{$date}/{$this->region}/s3/aws4_request";
        $stringToSign = "{$algorithm}\n{$now}\n{$credentialScope}\n" . hash('sha256', $canonicalRequest);

        // Derived signing key
        $kDate    = hash_hmac('sha256', $date, 'AWS4' . $this->secretKey, true);
        $kRegion  = hash_hmac('sha256', $this->region, $kDate, true);
        $kService = hash_hmac('sha256', 's3', $kRegion, true);
        $kSigning = hash_hmac('sha256', 'aws4_request', $kService, true);
        $signature = hash_hmac('sha256', $stringToSign, $kSigning);

        $authHeader = "{$algorithm} Credential={$this->accessKey}/{$credentialScope}, "
                    . "SignedHeaders={$signedHeaders}, Signature={$signature}";

        $reqHeaders = [
            "Host: {$hostHeader}",
            "x-amz-content-sha256: {$payloadHash}",
            "x-amz-date: {$now}",
            "Authorization: {$authHeader}",
        ];
        if ($contentType !== '') $reqHeaders[] = "Content-Type: {$contentType}";

        $ch = curl_init($url);
        $respHeaders = [];
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $reqHeaders,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_HEADERFUNCTION => function ($c, $h) use (&$respHeaders) {
                $line = trim($h);
                if (strpos($line, ':') !== false) {
                    [$k, $v] = array_map('trim', explode(':', $line, 2));
                    $respHeaders[strtolower($k)] = $v;
                }
                return strlen($h);
            },
        ]);
        if ($method === 'PUT' && $body !== '') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        $resp = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($resp === false) {
            return [0, [], null];
        }

        return [(int)$status, $respHeaders, $returnBody ? $resp : null];
    }
}
