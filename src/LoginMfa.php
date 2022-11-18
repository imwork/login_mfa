<?php
namespace Fish210717\LoginMfa;

use RobThree\Auth\TwoFactorAuth;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\QRCode;

class LoginMfa
{
    private $issuer = 'MyApp';

    // 2fa验证
    public function verify(string $secret,string $code) : bool
    {
        $tfa = new TwoFactorAuth($this->getIssuer());
        return $tfa->verifyCode($secret, $code);
    }


    // 获取2fa二维码和secret
    public function create(string $userName,string $secret = '') : string
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


    // 获取2fa二维码
    public function qrcode(string $text,int $size = 5)
    {
        $options = new QROptions([
            'version'    => 5,                             //二维码版本
            'outputType' => QRCode::OUTPUT_IMAGE_JPG,      //生成图片
            'eccLevel'   => QRCode::ECC_L,                 //错误级别
            'scale'=>$size,                                   //二维码大小
        ]);
        $qrcode = new QRCode($options);
        //将二维码直接生成base64格式的图片
        return $qrcode->render($text);
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