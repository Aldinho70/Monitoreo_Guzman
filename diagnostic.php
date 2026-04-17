<?php
echo "<pre>";

echo "1) DNS resolve:\n";
$ip = gethostbyname('hst-api.wialon.com');
echo "IP: $ip\n\n";

echo "2) fsockopen test (TCP 443):\n";
$start = microtime(true);
$fp = @fsockopen($ip, 443, $errno, $errstr, 5);
$time = round(microtime(true) - $start, 2);

if (!$fp) {
    echo "ERROR TCP: $errstr ($errno) - {$time}s\n\n";
} else {
    echo "TCP OK - {$time}s\n";
    fclose($fp);
}

echo "\n3) cURL verbose test:\n";

$ch = curl_init("https://hst-api.wialon.com");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_VERBOSE => true,
    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
    CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
]);

$response = curl_exec($ch);

if ($response === false) {
    echo "cURL ERROR: " . curl_error($ch) . "\n";
} else {
    echo "cURL OK\n";
}

curl_close($ch);

echo "</pre>";
