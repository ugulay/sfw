<?php


namespace Kernel;

use Kernel\Config;

class JWT
{
    const TYP = 'JWT';
    const ALG = 'HS256';
    const HMAC_ALG = 'sha256';

    static function base64UrlEncode($text)
    {
        return str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode($text)
        );
    }

    static function header()
    {
        $header = json_encode(['typ' => self::TYP, 'alg' => self::ALG]);
        return self::base64UrlEncode($header);
    }

    static function payload($data)
    {
        $data['iat'] = (int)(date('U'));
        $data['exp'] = (int)(date('U') + Config::get('JWT_EXPIRE'));
        return self::base64UrlEncode(json_encode($data));
    }

    static function signature($header = null, $payload = null)
    {
        return hash_hmac(self::HMAC_ALG, $header . "." . $payload, Config::get('JWT_SECRET'));
    }

    public static function encode($data)
    {
        $header = self::header();
        $payload = self::payload($data);
        $signature = self::signature($header, $payload);
        return $header . "." . $payload . "." . $signature;
    }

    public static function check($jwt = null)
    {

        $tokenParts = explode('.', $jwt);
        $header = base64_decode($tokenParts[0]);
        $payload = json_decode(base64_decode($tokenParts[1]), true);
        $signatureProvided = $tokenParts[2];

        $signature = self::signature($tokenParts[0], $tokenParts[1]);

        if (date('U') > $payload["exp"]) {
            return false;
        }

        if ($signature !== $signatureProvided) {
            return false;
        }

        return true;

    }

}