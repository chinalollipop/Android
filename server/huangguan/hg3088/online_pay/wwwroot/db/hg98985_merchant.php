<?php
/**
1）merchant_private_key，商户私钥;merchant_public_key,商户公钥；商户需要按照《密钥对获取工具说明》操作并获取商户私钥，商户公钥。
2）demo提供的merchant_private_key、merchant_public_key是测试商户号800003004321的商户私钥和商户公钥，请商家自行获取并且替换；
3）使用商户私钥加密时需要调用到openssl_sign函数,需要在php_ini文件里打开php_openssl插件
4）php的商户私钥在格式上要求换行，如下所示；
*/
	$merchant_private_key='-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALpxEboy50Mv/0gS
0XOARj1xZmFd7jTHYvLmLK9uvnBHx3knBjE33IAeESImXVQQdYrSw9V7w4IK0Dml
cgOBdFe9phPDKsoKo2RZwMqr22tXThDqHi/zTyx+ENKa7VnuAeJ/OqN1UsBb54yA
7/0wJjBMXXhmwzs6T0u522bl2d5PAgMBAAECgYBVRKMb6m30P35cYybh0TfOoA93
1nK8NrhdaMHlFhwI0/s5wIxHdEnnWljxqmQir0hizlP8ThczYWisBKupSMqBEbnx
FBgT1fPdrj2R/wjOSKMSeWaUift+duUabrpH+EGtybfFKl83RiQyGDKm79T9hU/D
ogTyDJUgo1AOafh/KQJBAO8CdJlY4EB7KF2WaHpx5ZFxGtEE8KO5M7+baveuxgMg
ZFbuDVGxN9CThBg4bk4rWGiP43c2QTu25rpYSmWg/IMCQQDHsfjpfwgiuudio3nj
oX04NQ+dVIoiSuI8LXYFrqFJxBpPO6AAuqfRg6wyY8cytc/Q5jtMsbGdB5ryrXf1
N8VFAkEA1W7/6KCxQWO5nDlge1eShdrW86jRq6MLTyPe1efA87HNaMOUKUt0aFIT
N/3MowmaHWXA3wgYINcoG2gUp8SnOQJBAIwGTWVc/9VqfUj7HELzP5ykCDjnyJxX
Kd/MeM9vLgVNjq00P/OC7p3I2HvU3x69weTXK4mRp9tWM94qqMy3uIkCQCPUBO/N
m4AvZFcRkIqghJk4c5Ke9GpDrWMUv6QFnLqtvM7aDx11015wtBDZGavTyIJuOrxC
ATulbmFpG4g26Hc=
-----END PRIVATE KEY-----';

	//merchant_public_key,商户公钥，按照说明文档上传此密钥到得宝商家后台，位置为"支付设置"->"公钥管理"->"设置商户公钥"，代码中不使用到此变量
	$merchant_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC6cRG6MudDL/9IEtFzgEY9cWZh
Xe40x2Ly5iyvbr5wR8d5JwYxN9yAHhEiJl1UEHWK0sPVe8OCCtA5pXIDgXRXvaYT
wyrKCqNkWcDKq9trV04Q6h4v808sfhDSmu1Z7gHifzqjdVLAW+eMgO/9MCYwTF14
ZsM7Ok9Ludtm5dneTwIDAQAB
	-----END PUBLIC KEY-----';
	
/**
1)dinpay_public_key，得宝公钥，每个商家对应一个固定的得宝公钥（不是使用工具生成的密钥merchant_public_key，不要混淆），
即为得宝商家后台"公钥管理"->"得宝公钥"里的绿色字符串内容,复制出来之后调成4行（换行位置任意，前面三行对齐），
并加上注释"-----BEGIN PUBLIC KEY-----"和"-----END PUBLIC KEY-----"
2)demo提供的dinpay_public_key是测试商户号1118004517的得宝公钥，请自行复制对应商户号的得宝公钥进行调整和替换。
3）使用得宝公钥验证时需要调用openssl_verify函数进行验证,需要在php_ini文件里打开php_openssl插件
*/
		$dinpay_public_key ='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCjkAZTvN/wAbO+52a
1HiwaDwNKC8Y9yEM6iuLyy1yGMdRythC5hjjKzr/SRadTD2AA2+hh9C
8xkcp+1/pnKP1peoNlpwD1FF0ToxFlx7VxD2JZf6Y8y8unFej71j+mV
b8Dw3Y0JgHRYtP89zd8pBplxF0QYhSnUdVuqPHJFHjAnwIDAQAB 
-----END PUBLIC KEY-----'; 	
	

?>