google 双重认证

示例:
$mfa    = new LoginMfa();
$data     = json_decode(($mfa->create($domain . '-' . $userName)), true);

$result = $mfa->verify($data['secret'], $data['code']);