<?php
/**
 * Upload helper used by admission/app/formhandler.php.
 *
 * Primary path: S3 (ZevCloud Storage) when configured. Files land at
 *   admission/storage/{type}/{filename}
 * and are served back through admission/storage/index.php (the proxy)
 * so existing img URLs keep working.
 *
 * Fallback path: local disk (cPanel-era behavior). Kept so local dev
 * and any container that boots without S3 env vars still works.
 *
 * Returns true on success, false on failure — mirrors the
 * `move_uploaded_file` return contract so call sites stay symmetric.
 */
require_once __DIR__ . '/s3client.php';

function admission_storage_upload(string $tmpPath, string $type, string $filename): bool {
    static $allowed = ['passport', 'credential', 'payment'];
    if (!in_array($type, $allowed, true)) return false;

    $s3 = new S3Client();
    if ($s3->isConfigured()) {
        // Resolve a sensible content-type from the extension so the
        // proxy doesn't need to second-guess later. mime_content_type
        // works on the temp upload because PHP keeps it around with
        // the original MIME until the request ends.
        $contentType = function_exists('mime_content_type')
            ? (mime_content_type($tmpPath) ?: 'application/octet-stream')
            : 'application/octet-stream';
        return $s3->putFile($tmpPath, "{$type}/{$filename}", $contentType);
    }

    // Local disk fallback. Mirrors the original cPanel layout.
    $dir = __DIR__ . "/../storage/{$type}/";
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    return move_uploaded_file($tmpPath, $dir . $filename);
}
