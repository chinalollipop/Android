<?php
/**
1）merchant_private_key，商户私钥;merchant_public_key,商户公钥；商户需要按照《密钥对获取工具说明》操作并获取商户私钥，商户公钥。
2）demo提供的merchant_private_key、merchant_public_key是测试商户号800003004321的商户私钥和商户公钥，请商家自行获取并且替换；
3）使用商户私钥加密时需要调用到openssl_sign函数,需要在php_ini文件里打开php_openssl插件
4）php的商户私钥在格式上要求换行，如下所示；
*/
	$merchant_private_key='-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALKSpbiqtooVQIGn
lj1GHLcFGGEbQU4VrE69TUhEnkbYgf1cNQwWhpvyaoZXFx4/3Dr7tDw8zAQL5wVk
pD68O69q0fiXBS6k9Iz9AI/yOMK6+zvYcluVccUj6Lud5nZARJvqmOtlwCsLpQFO
qaEXDEyqMPFImKQo4zjsp9IPRuuVAgMBAAECgYBDjWZTrYDQS6nTYmpbU6KZObw3
P/A8ccZ1IveRVyPo+Q5zoeYPZJYXeGUD3P5J2y6cwYs1HmqvPTFvwNrBQMPyvjVG
01PtWyHQtEZNEvK1rW3Rtv3r6Q6yUyUB4AEefB0TT+TBDvjwelkLgiJYOfYv+4+3
PD+OXTPVaH4PlaEuAQJBAN77+YRr0emG4NbXOOjjuVXyFmHF+LX7uUVpZED5Prv8
rhNwTUyBgAuhg0k+P58fbGY5TQ8BIJCzPJLmvqRa/wUCQQDNA0u52bTNHWvRVYVd
wlHW/2hTZ2EQ6nzbXYDHtPoV4YB/7LerG9A41OyEU5DCCljFf6q1w1n+br2GPmPr
8j9RAkBYY1Ui0oJgqSBSyPk8B5idotqQYyMVL/TeuMoOnz3o0l5GQkvBucuB0MHz
tIMPlisn0irjebTo5sNqD6EeERvpAkEAhrGtpROGGGVGGyjnEKzE/E6eCn4XZffJ
xI5wN8WNsaaQuHucSI1jhpNOObp1lycZH9k7HWSV0faMCpEfMTvdoQJBALSyP7MI
OOsmTveqATQuRARHLdP01vPYpFqRl3wUTazJ9ILYXZO76BV0oze5BGbly27jTxCJ
V/FLSTWe1+Cukho=
-----END PRIVATE KEY-----';

	//merchant_public_key,商户公钥，按照说明文档上传此密钥到得宝商家后台，位置为"支付设置"->"公钥管理"->"设置商户公钥"，代码中不使用到此变量
	$merchant_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC5ak34HC0giMqBunEGj8v3tG7K
ABwMbueYbHGw3f8bus3Uqj2PEBmoDi0FwaeHZjXqCwkA/GZEigY3C7fDGqnmZtU6
XWPTGsyK6GvIFXfYXpfyC1g4zc72Wt4aTMlN8ybFn6tcqhXYT/SySaPVVAN0NPaj
JoRoCG/oTCocToNqPQIDAQAB
	-----END PUBLIC KEY-----';
	
/**
1)dinpay_public_key，得宝公钥，每个商家对应一个固定的得宝公钥（不是使用工具生成的密钥merchant_public_key，不要混淆），
即为得宝商家后台"公钥管理"->"得宝公钥"里的绿色字符串内容,复制出来之后调成4行（换行位置任意，前面三行对齐），
并加上注释"-----BEGIN PUBLIC KEY-----"和"-----END PUBLIC KEY-----"
2)demo提供的dinpay_public_key是测试商户号1118004517的得宝公钥，请自行复制对应商户号的得宝公钥进行调整和替换。
3）使用得宝公钥验证时需要调用openssl_verify函数进行验证,需要在php_ini文件里打开php_openssl插件
*/
		$dinpay_public_key ='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC5ak34HC0giMqBunE
Gj8v3tG7KABwMbueYbHGw3f8bus3Uqj2PEBmoDi0FwaeHZjXqCwkA/G
ZEigY3C7fDGqnmZtU6XWPTGsyK6GvIFXfYXpfyC1g4zc72Wt4aTMlN8
ybFn6tcqhXYT/SySaPVVAN0NPajJoRoCG/oTCocToNqPQIDAQAB
-----END PUBLIC KEY-----'; 	
	

?>