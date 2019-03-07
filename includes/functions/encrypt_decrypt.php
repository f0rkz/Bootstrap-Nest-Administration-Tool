<?php
/**
 * Returns an encrypted & utf8-encoded
 */
function encrypt($pure_string, $encryption_key) {
    if (mb_strlen($encryption_key, '8bit') !== 32) {
        throw new Exception('Needs a 256-bit key!');
    }
    $iv_size = openssl_cipher_iv_length('aes-256-cbc');
    $iv = openssl_random_pseudo_bytes($iv_size);
    $encrypted_string = openssl_encrypt($pure_string, 'aes-256-cbc', $encryption_key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $encrypted_string);
}

/**
 * Returns decrypted original string
 */
function decrypt($encrypted_string, $encryption_key) {
    if (mb_strlen($encryption_key, '8bit') !== 32) {
        throw new Exception('Needs a 256-bit key!');
    }
    $encrypted_string = base64_decode($encrypted_string);
    $iv_size = openssl_cipher_iv_length('aes-256-cbc');
    $iv = mb_substr($encrypted_string, 0, $iv_size, '8bit');
    $ciphertext = mb_substr($encrypted_string, $iv_size, null, '8bit');
    $decrypted_string = openssl_decrypt($ciphertext, 'aes-256-cbc', $encryption_key, OPENSSL_RAW_DATA, $iv);
    return $decrypted_string;
}