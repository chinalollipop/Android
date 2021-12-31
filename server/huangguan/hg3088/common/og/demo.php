
<?php
//这是 获取token
$request = new HttpRequest();
$request->setUrl('http://api01.oriental-game.com:8085/token');
$request->setMethod(HTTP_METH_GET);

$request->setHeaders(array(
    'cache-control' => 'no-cache',
    'Connection' => 'keep-alive',
    'Accept-Encoding' => 'gzip, deflate',
    'Host' => 'api01.oriental-game.com:8085',
    'Postman-Token' => 'c3cd75de-4aed-499d-b18a-c8e579606c8d,7163b8b0-9759-4ca1-9566-09c2c41fcce9',
    'Cache-Control' => 'no-cache',
    'Accept' => '*/*',
    'User-Agent' => 'PostmanRuntime/7.16.3',
    'X-Key' => 'cH4XdpWsGPMySdD1',
    'X-Operator' => 'ogcstest1'
));

try {
    $response = $request->send();

    echo $response->getBody();
} catch (HttpException $ex) {
    echo $ex;
}
?>
<?php
//这是 注册会员
$request = new HttpRequest();
$request->setUrl('http://api01.oriental-game.com:8085/register');
$request->setMethod(HTTP_METH_POST);

$request->setHeaders(array(
    'cache-control' => 'no-cache',
    'Connection' => 'keep-alive',
    'Content-Length' => '170',
    'Accept-Encoding' => 'gzip, deflate',
    'Host' => 'api01.oriental-game.com:8085',
    'Postman-Token' => 'de2f0b88-1aab-40da-ac73-d57ac3d08437,f731a754-3281-4d23-928d-69387a4d98c2',
    'Cache-Control' => 'no-cache',
    'Accept' => '*/*',
    'User-Agent' => 'PostmanRuntime/7.16.3',
    'Content-Type' => 'text/plain',
    'X-Token' => 'n0HntAy28S1KtSDwCA0Y3h0v4kun0i6tEB0pv1ebA5yWTK3lIWAknFmmZG42E5tGNudDw7qCC99Hr7KXe6PMPraTiRJgiSYzsrJe'
));

$request->setBody('{
    "username": "test555",
    "country": "China",
    "fullname": "Test",
    "language": "cn",
    "email": "854525452@qq.com",
    "birthdate": "1990-02-18"
}');

try {
    $response = $request->send();

    echo $response->getBody();
} catch (HttpException $ex) {
    echo $ex;
}
?>
<?php
//这是 更新余额 （转账）
$request = new HttpRequest();
$request->setUrl('http://api01.oriental-game.com:8085/game-providers/1/balance');
$request->setMethod(HTTP_METH_POST);

$request->setHeaders(array(
    'cache-control' => 'no-cache',
    'Connection' => 'keep-alive',
    'Content-Length' => '110',
    'Accept-Encoding' => 'gzip, deflate',
    'Host' => 'api01.oriental-game.com:8085',
    'Postman-Token' => '118f299c-8255-4120-8bb1-f3f2db0629c4,0e5d87d4-a078-427a-8e21-f0cdb453b7f9',
    'Cache-Control' => 'no-cache',
    'Accept' => '*/*',
    'User-Agent' => 'PostmanRuntime/7.16.3',
    'Content-Type' => 'text/plain',
    'X-Token' => 'n0HntAy28S1KtSDwCA0Y3h0v4kun0i6tEB0pv1ebA5yWTK3lIWAknFmmZG42E5tGNudDw7qCC99Hr7KXe6PMPraTiRJgiSYzsrJe'
));

$request->setBody('{
    "username": "test555",
    "balance": 100,
    "action": "in",
    "transferId": "14525546565245"
}');

try {
    $response = $request->send();

    echo $response->getBody();
} catch (HttpException $ex) {
    echo $ex;
}
?>

<?php
//转账确认
$request = new HttpRequest();
$request->setUrl('http://api01.oriental-game.com:8085/api/checkOGTransfer');
$request->setMethod(HTTP_METH_POST);

$request->setHeaders(array(
    'cache-control' => 'no-cache',
    'Connection' => 'keep-alive',
    'Content-Length' => '68',
    'Accept-Encoding' => 'gzip, deflate',
    'Host' => 'api01.oriental-game.com:8085',
    'Postman-Token' => 'bc474e43-430f-4d42-b4ab-c914ea4106da,f1457528-0b7a-41a6-886f-f84b655517d2',
    'Cache-Control' => 'no-cache',
    'Accept' => '*/*',
    'User-Agent' => 'PostmanRuntime/7.16.3',
    'Content-Type' => 'text/plain',
    'X-Token' => 'n0HntAy28S1KtSDwCA0Y3h0v4kun0i6tEB0pv1ebA5yWTK3lIWAknFmmZG42E5tGNudDw7qCC99Hr7KXe6PMPraTiRJgiSYzsrJe'
));

$request->setBody('{
    "username": "test555",
    "transferId": "14525546565245"
}');

try {
    $response = $request->send();

    echo $response->getBody();
} catch (HttpException $ex) {
    echo $ex;
}
?>
<?php
//取得游戏金钥

$request = new HttpRequest();
$request->setUrl('http://api01.oriental-game.com:8085/game-providers/1/games/oglive/key');
$request->setMethod(HTTP_METH_GET);

$request->setQueryData(array(
    'username' => 'test555'
));

$request->setHeaders(array(
    'cache-control' => 'no-cache',
    'Connection' => 'keep-alive',
    'Accept-Encoding' => 'gzip, deflate',
    'Host' => 'api01.oriental-game.com:8085',
    'Postman-Token' => 'e6dea488-6d88-497b-99b9-9e42b994efbd,9028f673-73eb-4f60-9192-1305ee846c88',
    'Cache-Control' => 'no-cache',
    'Accept' => '*/*',
    'User-Agent' => 'PostmanRuntime/7.16.3',
    'X-Token' => 'n0HntAy28S1KtSDwCA0Y3h0v4kun0i6tEB0pv1ebA5yWTK3lIWAknFmmZG42E5tGNudDw7qCC99Hr7KXe6PMPraTiRJgiSYzsrJe'
));

try {
    $response = $request->send();

    echo $response->getBody();
} catch (HttpException $ex) {
    echo $ex;
}

?>
<?php
//获取 玩游戏 链接
$request = new HttpRequest();
$request->setUrl('http://api01.oriental-game.com:8085/game-providers/1/play');
$request->setMethod(HTTP_METH_GET);

$request->setQueryData(array(
    'key' => '67a17dd934c01fffcf1690351924b6b279e21b47cf05ef83d39935cb770418ba67060f17fe0e47ae7bbf5687832eab3ee26cfea3cfb0cb29a3d6df700791f129bd3c6a3b8e8a6f74eec62f04357f1979c6f12b4303c9'
));

$request->setHeaders(array(
    'cache-control' => 'no-cache',
    'Connection' => 'keep-alive',
    'Accept-Encoding' => 'gzip, deflate',
    'Host' => 'api01.oriental-game.com:8085',
    'Postman-Token' => 'b05064b3-548f-4d2d-a97b-9b436a4f01f0,acc39568-e310-48eb-a9c1-974d1a1208b6',
    'Cache-Control' => 'no-cache',
    'Accept' => '*/*',
    'User-Agent' => 'PostmanRuntime/7.16.3'
));

try {
    $response = $request->send();

    echo $response->getBody();
} catch (HttpException $ex) {
    echo $ex;
}

?>

<?php
// 获取下注记录

$request = new HttpRequest();
$request->setUrl('https://tigerapi-testing.oriental-game.com:38888/transaction');
$request->setMethod(HTTP_METH_POST);

$request->setHeaders(array(
    'cache-control' => 'no-cache',
    'Connection' => 'keep-alive',
    'Content-Length' => '645',
    'Accept-Encoding' => 'gzip, deflate',
    'Content-Type' => 'multipart/form-data; boundary=--------------------------511502305845470750065764',
    'Host' => 'tigerapi-testing.oriental-game.com:38888',
    'Postman-Token' => '4550ccd3-d0ff-4b38-9960-2ee0853d88ea,7f66f49d-1dd2-4059-9f86-73cec8035e27',
    'Cache-Control' => 'no-cache',
    'Accept' => '*/*',
    'User-Agent' => 'PostmanRuntime/7.16.3',
    'content-type' => 'multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW'
));

$request->setBody('------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="Operator"

ogcstest1
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="Key"

cH4XdpWsGPMySdD1
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="SDate"

2019-08-21 10:10:00
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="EDate"

2019-08-21 10:20:00
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="Provider"

og
------WebKitFormBoundary7MA4YWxkTrZu0gW--');

try {
    $response = $request->send();

    echo $response->getBody();
} catch (HttpException $ex) {
    echo $ex;
}

?>

<?php
//获取转账记录
$request = new HttpRequest();
$request->setUrl('https://tigerapi-testing.oriental-game.com:38888/transfer');
$request->setMethod(HTTP_METH_POST);

$request->setHeaders(array(
    'cache-control' => 'no-cache',
    'Connection' => 'keep-alive',
    'Content-Length' => '645',
    'Cookie' => 'SERVERID=8de1060601c4dca5396f403e35352be7|1567394553|1567394553',
    'Accept-Encoding' => 'gzip, deflate',
    'Content-Type' => 'multipart/form-data; boundary=--------------------------832468885468916315622170',
    'Host' => 'tigerapi-testing.oriental-game.com:38888',
    'Postman-Token' => '90f0ae79-90bd-49bd-b0ce-b780aaa293ae,ee2e76ac-d3ef-4688-b0d6-0e22b86e66a8',
    'Cache-Control' => 'no-cache',
    'Accept' => '*/*',
    'User-Agent' => 'PostmanRuntime/7.16.3',
    'content-type' => 'multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW'
));

$request->setBody('------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="Operator"

ogcstest1
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="Key"

cH4XdpWsGPMySdD1
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="SDate"

2019-08-23 09:10:00
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="EDate"

2019-08-23 09:20:00
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="Provider"

og
------WebKitFormBoundary7MA4YWxkTrZu0gW--');

try {
    $response = $request->send();

    echo $response->getBody();
} catch (HttpException $ex) {
    echo $ex;
}