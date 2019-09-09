<?php


namespace Kernel;

use Kernel\Config;

class JWT
{
    const TYP = 'JWT';
    const ALG = 'HS256';
    const HMAC_ALG = 'sha256';

    private $header,
        $header64,
        $payload,
        $payload64,
        $signature = null;

    function base64UrlEncode($text)
    {
        return str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode($text)
        );
    }

    function header()
    {
        $this->header = json_encode(['typ' => self::TYP, 'alg' => self::ALG]);
        $this->header64 = str_replace(['+', '/', '='], ['-', '_', ''], $this->base64UrlEncode($this->header));
        return $this;
    }

    function payload($data)
    {
        $data['exp'] = date('U') + Config::get('JWT_EXPIRE');
        $this->payload = json_encode($data);
        $this->payload64 = str_replace(['+', '/', '='], ['-', '_', ''], $this->base64UrlEncode($this->payload));
        return $this;
    }

    function signature()
    {
        $this->signature = hash_hmac(self::HMAC_ALG, $this->header64 . "." . $this->payload64, Config::get('JWT_SECRET'), true);
        return $this;
    }

    function getJwt()
    {
        $jwt = $this->header64 . "." . $this->payload64 . "." . $this->signature;
        return $jwt;
    }

    function checkJWT($jwt = null)
    {
        $tokenParts = explode('.', $jwt);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];

        $expiration = $payload["exp"];

        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode($payload);
        $signature = hash_hmac(self::HMAC_ALG, $base64UrlHeader . "." . $base64UrlPayload, Config::get('JWT_SECRET'), true);
        $base64UrlSignature = $this->base64UrlEncode($signature);

        if (date('U') > $expiration) {
            return false;
        }

        if ($base64UrlSignature !== $signatureProvided) {
            return false;
        }

        return true;

    }

}