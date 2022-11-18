<?php
use RobThree\Auth\TwoFactorAuth;

class mfa
{
    private $issuer = 'MyApp';

    // 2fa验证
    public function verify(string $secret,string $code) : bool
    {
        $tfa = new TwoFactorAuth($this->getIssuer());
        return $tfa->verifyCode($secret, $code);
    }


    // 获取2fa二维码和secret
    public function create(string $userName,string $secret = '')
    {
        $tfa = new TwoFactorAuth($this->issuer);
        if (!$secret) {
            $secret = $tfa->createSecret(160);
        }
        $text = 'otpauth://totp/' . $userName . '?secret=' . $secret . '&issuer=shopyy&period=30&algorithm=SHA1&digits=6';
        if ($secret) {
            return json_encode(['status' => 1,'data'=>['secret' => $secret,'text' => urlencode($text)]]);
        }
        return json_encode(['status' => 0,'data'=>['secret' => '','text' => '']]);
    }

    /**
     * @return string
     */
    public function getIssuer()
    {
        return $this->issuer;
    }

    /**
     * @param string $issuer
     */
    public function setIssuer($issuer)
    {
        $this->issuer = $issuer;
    }
}