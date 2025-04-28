<?php
namespace App\Utils;

class Crypto
{

    private static string $encryptionKey = "ff88996a9b1d4752effc0d3e55fbf6b0d091d1aac868f384e0c96c91bd1e581b";
    private static string $cipher = 'AES-256-CBC';

    public static function encrypt(string $data): string
    {
        $ivLength = openssl_cipher_iv_length(self::$cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);
        $encrypted = openssl_encrypt($data, self::$cipher, self::$encryptionKey, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public static function decrypt(string $data): string
    {
        $data = base64_decode($data);
        $ivLength = openssl_cipher_iv_length(self::$cipher);
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);
        return openssl_decrypt($encrypted, self::$cipher, self::$encryptionKey, 0, $iv);
    }
}
