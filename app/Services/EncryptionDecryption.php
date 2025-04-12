<?php
// https://stackoverflow.com/questions/39693312/the-only-supported-ciphers-are-aes-128-cbc-and-aes-256-cbc-with-the-correct-key
namespace App\Services;
use Config;
class EncryptionDecryption
{
    public function encrypt($plainTextToEncrypt, $key = 'JS_KEY'): string
    {
      // app.cipher = AES-256-CBC
      $key = getenv($key);
      if(empty($key)) {
        $key = 'ABC56EABCDDEF123ERD4EF123ERD456E';
      }
      $newEncrypter = new \Illuminate\Encryption\Encrypter( $key, Config::get( 'app.cipher' ) );
      return $newEncrypter->encryptString( $plainTextToEncrypt );
    }

    public function decrypt($plainTextToEncrypt, $key = 'JS_KEY'): string
    {
      $newEncrypter = new \Illuminate\Encryption\Encrypter( getenv($key), Config::get( 'app.cipher' ) );
      return $newEncrypter->decryptString( $plainTextToEncrypt );
    }
}
