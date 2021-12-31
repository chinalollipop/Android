<?php
class Config{

    const nerchNo = "10811";     // 测试:1001   0086:10811

    //支付请求url
    const payurl = "http://apidemo.jumingpay.com.cn/api/payOrder/create";

    //支付订单查询url
    const payQuery ="http://apidemo.jumingpay.com.cn/api/payOrder/query";

    //配置支付平台公钥
    // 测试
    //const publicKey = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCRwmzgyPl3U7qU6YRRGEvGmW6Mlcb3KNSyo7S01PUAYbbN/iAOmBsuk9tcdCWumMGmSj+QY52X+Co2rdaPrXaBHYvwe9zWs2WmeBxmsMl1xmGviLqCn88a2hYeK2+f4XiMoqx+CeGl3Q9/I/uyTiLqcL/Jw3QgTHngFMQ9LXQYqwIDAQAB";
    // 0086
    const publicKey = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCtKec6Gs7DFMO4xgp//lqF3D5LIVyru4LL2lx9FAopWxUXk8L3AP5Xb4VxDTZkplugFIW8O2GAoGWkR+LbmbO9G/tFbwGHolLXg30FLFlLAw+GCjo1prgtbBLWiVkLl1Vjgv/elw9wqQzYlrR9MDKx3BqDs5ZOyvp/xthMfOlglQIDAQAB";

    //配置支付私钥(商户私钥)
    // 测试
    //const privateKey = "MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAK0zsS3BIDGbJoQXi1iJHVW935X7LnFomj+NW/+QCq6lA/siN8r28eG0/LonTUlpKK4b6gbuUadp9e3nW6bH5ODSfRGPLlM9OBWaN5Zs9norEVPK6XPPTfe/big6kqzGKU67z3zM8T070PVdnMALqoIq3Avz59k4MCe94osFfl8xAgMBAAECgYAdfbrCdqrbp3ZUcYnZhmdHTTA/4mgTCWOSRKiQiF85Q4G9BiOH3Kps6xtJOx3uzQgPNVOQ4I1ouyMT4hv59vligtw8L1knF5ty4YM8T4Zq+ooNW05H/Ns8lQUxdWnTnekX0THZeRH5BXqtRBGZw0inST0h1jMRJ6TRzpO4Zlr7PQJBANZowaU6M9eCBYozUi3IRhBtoXro1mwLjYXmijlkcPx02fMNY3r/7U6wLYb19HAUm6a2l3ezWHPumpPiglKlP4cCQQDOzKSfP+MXMbomIRWuiv0jWzpYXHy9RncoWh0tIRZsP9pKxfANBGuJeo4Z/J84Ji/iZtvCLX9xUxFu+NS8OOmHAkAK6SPJi6+trNEpWjk5WTKvjVSlU4ntz5yxDq1EBGd3gV7B7pF8Zd+mnHKEpql8tp/BGROWJMtAgwjcs68cE4qrAkA/DWpMG+CTm9fT9FZ2B26zLweVFW37D9cY+JDYx7PcgYN/NObCMUzQeAuHpNyu9AW5k/8BL3oiBV/VZA0I7plVAkByXYQmtWjQPqJ8grFus/QsnpuzB9pwz00H7c+yoI8BgmikHKhBew1LOtK7cRyRspz9/W3oHPQ/c6rdi7WqVKD1";
    // 0086
    const privateKey = "MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBAOb5B3acL+hDvMUH/IXbbw/iDb4uDF5y+hG4E/fWI2PZKO86QVamdJiYMno1y/qaA0005cD8Qu9KUMA8XfYV/IpDaAQO9v2c/n8XcVQddtvtSjmfoC0V5IlgUZrR8H7npe+R+lfLbuKrvfccIICBiHEs+ZXODffGLUq1axkQTZpxAgMBAAECgYEAg7bNn8EPvNcsBfw48CijnB4gEiz2VsIggwxDLtkJ033aTxoceopwATkByBl3mhBMA7I/tx7kGMt7YzTJrHT7TtQbUHTDyCP+k49twoQ5Z7HWOLb5VYpXjOEZyUg2/PNb7eBG4cSYiWrMG3fIRwI5mv+CKA0F0qEvA3rXNG9ParECQQD4tJVcvR83SFodLoUpXsL/0Sic+cW9p3JCobqhcB0j6i/M2ckRGARmL2UxCrayrdePx2ipzo0sh06C9+z3uMhVAkEA7b9Mo1w28LC6sC1v7Oi+rBIrvLgrMP7BSF7tor+gJNhBsmT7A536Auk2YmJUg6wkis9V9y7uYLp7dHGTCEJVrQJBALYPQ7OSwlIuxcDM+C/Trb2k+C0JbuapuCvsxnk9YCeVXI6B8v1vbnD7SwE+jYV5Wu5mlEiR6qhpGGEpaq/g9c0CQQC90X+56jU/RIs5gH2ddFJQBg3/ljd93diqFIi86SxOXo9NhIxZXfcv/c1bDdEWdAmU/mdPfJv8hMduuANBk9zdAkBc7zh7A8D8ni+ILpsMPJ+0DOrtUZqcOMg5hIwAGdyWTLr/IBuMtY/2ag8pstrN+5s2LVc9Os1ByYp4c1k84pkk";

    //配置代付请求url
    //const remiturl = "http://apidemo.jumingpay.com.cn/api/remitOrder/create";   //测试
    const remiturl = "http://api.jumingpay.com.cn/api/remitOrder/create";   //正式

    //配置代付查询url
    const remitQuery = "http://apidemo.jumingpay.com.cn/api/remitOrder/query";

    const balanceUrl = "http://apidemo.jumingpay.com.cn/api/balance/query";


}