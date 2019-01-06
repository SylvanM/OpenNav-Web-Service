<?php

ini_set('display_errors',1); 
error_reporting(E_ALL);

include('Crypt/RSA.php');

function encrypt($plaintext, $plainKey) {

    $rsaEncrypt = new Crypt_RSA();

    $key = str_replace(' ', '+', $plainKey);
    $rsaEncrypt->loadKey($key);
    $rsaEncrypt->setPublicKey();

    // $rsaEncrypt->setEncryptionMode(Crypt_RSA::CRYPT_RSA_ENCRYPTION_PKCS1);

    $ciphertext = $rsaEncrypt->encrypt($plaintext);

    return $ciphertext;
}

function decrypt($ciphertext, $plainKey) {

    $rsaDecrypt = new Crypt_RSA();

    $key = str_replace(' ', '+', $plainKey);
    $rsaDecrypt->loadKey($key);
    $rsaDecrypt->setPrivateKey();

    // $rsa->setEncryptionMode(Crypt_RSA::CRYPT_RSA_ENCRYPTION_PKCS1);

    $plaintext = $rsaDecrypt->decrypt($ciphertext);

    return $plaintext;
}

?>
