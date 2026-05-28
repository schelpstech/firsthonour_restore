<?php
/**
 * Admission storage proxy.
 *
 * Existing image URLs in the codebase look like:
 *   /admission/storage/passport/FORM-ABC.jpg
 *   /admission/storage/credential/FORM-ABC.pdf
 *   /admission/storage/payment/FORM-ABC.png
 *
 * Files used to live on local disk under those paths (cPanel days).
 * They now live in S3 (ZevCloud Storage). We keep the URLs identical
 * so every img/iframe tag in the codebase keeps working — Apache's
 * mod_rewrite routes any request for a file under storage/ to this
 * script, which fetches the bytes from S3 and streams them back.
 *
 * Routing — see admission/storage/.htaccess:
 *   /admission/storage/passport/FOO.jpg → index.php?type=passport&file=FOO.jpg
 *
 * Local fallback: if the object isn't in S3 (e.g. dev environment
 * without S3 configured, or a file that was uploaded before the
 * migration and hasn't been synced), fall back to disk. The
 * .gitkeep'd folders are empty on the deployed container so the
 * fallback only kicks in during local development.
 */
require_once __DIR__ . '/../lib/s3client.php';

$type = $_GET['type'] ?? '';
$file = $_GET['file'] ?? '';

// Whitelist the three known subfolders. Anything else 404s — we
// don't want an attacker probing arbitrary keys via this proxy.
$allowed = ['passport', 'credential', 'payment'];
if (!in_array($type, $allowed, true) || $file === '') {
    http_response_code(404);
    exit('Not found');
}

// Path traversal guard. File names are application-controlled
// (`FORM-XXXXXX.ext`) so a slash or `..` is always malicious.
if (strpos($file, '/') !== false || strpos($file, '..') !== false) {
    http_response_code(400);
    exit('Bad request');
}

$relativeKey = "{$type}/{$file}";

// Try S3 first.
$s3 = new S3Client();
if ($s3->isConfigured() && $s3->streamObject($relativeKey)) {
    exit;
}

// Fall back to local disk (dev mode / pre-migration files).
$localPath = __DIR__ . '/' . $relativeKey;
if (is_readable($localPath)) {
    $ct = mime_content_type($localPath) ?: 'application/octet-stream';
    header('Content-Type: ' . $ct);
    header('Cache-Control: private, max-age=300');
    readfile($localPath);
    exit;
}

http_response_code(404);
exit('Not found');
