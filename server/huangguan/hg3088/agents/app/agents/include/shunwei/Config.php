<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/24
 * Time: 18:58
 */

class Config
{
    //商户号
    //const merCode = "M1000047"; // 测试
    const merCode = "M1000455"; // 7557

    //唯一标识
    //const headerKey = "H190808200904950070176";  //测试
    const headerKey = "H190808200904950070176";  //7557

    //签名秘钥
    //const signKey = "4EF484745BCFE1EE44CAD08F5F59D267"; //测试
    const signKey = "4EF484745BCFE1EE44CAD08F5F59D267"; //7557

    //RSA支付加密公钥
    //const payPublicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxgeep3qdDMlSW7MBl64ibJg99kqtptCSSlD2sk3b6uvmbo2Pz1tFDTwp7QEDgwpLANYgtmZhItIdd4sSvEtNFCkU+l1Y+Jv1IA/HIc4qL6XkCuXN4dxwY3xyHEZOYpK/Oa0NSQ+ErnSaPPrxKR5YqdQdfgI2oTImLtR/VGNuDKUYX7fXFfBsuOgEYTi+v2AzY317MEN+aTikvVqpugLIuZEtToRt7gCqju3bKvkX2MV+8jiqJJt2uTfuXlV3NYmpowTfo6Fb8cX4HrmEVP8gL92N8Tlu/Xww0JFtoNvltoO9la3K4EYKrQOYa0JTe5wjRGPmdQmmFxOkddpZNXZMFwIDAQAB"; //测试
    const payPublicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxgeep3qdDMlSW7MBl64ibJg99kqtptCSSlD2sk3b6uvmbo2Pz1tFDTwp7QEDgwpLANYgtmZhItIdd4sSvEtNFCkU+l1Y+Jv1IA/HIc4qL6XkCuXN4dxwY3xyHEZOYpK/Oa0NSQ+ErnSaPPrxKR5YqdQdfgI2oTImLtR/VGNuDKUYX7fXFfBsuOgEYTi+v2AzY317MEN+aTikvVqpugLIuZEtToRt7gCqju3bKvkX2MV+8jiqJJt2uTfuXlV3NYmpowTfo6Fb8cX4HrmEVP8gL92N8Tlu/Xww0JFtoNvltoO9la3K4EYKrQOYa0JTe5wjRGPmdQmmFxOkddpZNXZMFwIDAQAB"; //7557

    //RSA代付加密公钥
    //const remitPublickey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA6hksPJPm4wYoAuR2BNV2qphSUc6oW/hZBxoFB1KyH0Tqxlq2X2U8G3Fwmjb8DZ4KS1/4ePknAKm8YDHfNoyGcQBtdtenQ0haLN1umXt3/4C3zBHk89YAdgUR9j/sBZNkZy6EzwSlx9QuGhtaiWmyv9JxLNEJCV3y7LuUoUWUT/VMO+HC/2OJc0FlCmFXN6nOtGwOgD2PQCtuiFQJvES+z+5GOAnbKzAau1YHhKPMMyViIw0N6VWCeJyADDNQ+voj/bE6cioDEBNAbforQJjURV3SndQA5AlklFFVsKzpU0SxconM/EgVZ+23DgkRjTfxJJwvbWHOvBBcU9rFRlrDxQIDAQAB"; //测试
    const remitPublickey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA6hksPJPm4wYoAuR2BNV2qphSUc6oW/hZBxoFB1KyH0Tqxlq2X2U8G3Fwmjb8DZ4KS1/4ePknAKm8YDHfNoyGcQBtdtenQ0haLN1umXt3/4C3zBHk89YAdgUR9j/sBZNkZy6EzwSlx9QuGhtaiWmyv9JxLNEJCV3y7LuUoUWUT/VMO+HC/2OJc0FlCmFXN6nOtGwOgD2PQCtuiFQJvES+z+5GOAnbKzAau1YHhKPMMyViIw0N6VWCeJyADDNQ+voj/bE6cioDEBNAbforQJjURV3SndQA5AlklFFVsKzpU0SxconM/EgVZ+23DgkRjTfxJJwvbWHOvBBcU9rFRlrDxQIDAQAB"; //7557

    //RSA回调解密私钥
    //const privateKey = "MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCGmnOJ8MO6vymgyBX4OBr+ZTEF5Bv3y0cjL4JMIyt6+9ZOUmMKBbAe46W+LDMyP1W/bcfT2KpmptRM/JwKqnn3OskFYS7zj5k3K+bZFxuNgd3+WdTyfuY5gM0JO9hfwoERQRLQOXvGyFuJZ3sPlonBzlUQw6NJd+f8k8ZHCniZ+3tCs/vJIvXPcGLP7HWyQsJxMEfqCkutlFB/QXt+/YzLbwJkrv/fF9OPoygxuVX0YTXCk2RQM6P7jAsZszCJMy6DKsb4xYBGEnv/moFBGZO0fr1OY4XD6mAANyOJX2KUVPOrUEmGyXZxcm30fSbJe0vlEVljRp5FSQd0WGMxWpbrAgMBAAECggEAMc9eCiGqjKTJBtKELsK0WoefyIpNPzleLcsa8InjQTOIbptWQxMvxX6AiwtYNrPxS1GYrPB0vl/mGEcyh0/mqnJ6iR8ZfNAa5qu8VYGTlJ4GRGWBw+rpz0W2mkAnJeiYUoY+LAfZognqtcIxa+dNOwbbu8Rp3Hoewvz8VrWtQ22YPPBTqCtbwZi57VWmakmV10clJv+MPPoorigNsuj5DKqx/Kvcc+1WdgB7HMP2YDj/HYlVMq9Ib/iOaCVxnm263zbQ+F4mo0IGhXatNftF7TrYYbvK1f0Y6B4OGfkJGQFgkm/B2ZNd+rxDLaA72g9NrWTynhAGj7Fu9dUcTsYPIQKBgQD+c6ZgpublL+l0mKsFLI3T0oshOzFkkRwkiCzF1FYMFBx/P1BJ3jbcmP/WpE8ZdQ3QLEm52VdkBk785aZde5b5muBM24/Rq3UBAvXnwpXVVz0/sdsPsfOEGzQKu7CZSl7i6n0hwLessLLv5ytZGKjtLhSlxMOdncnBQZYB+8aFBQKBgQCHbB4xhyikmxap60ITVglgnRKMFK660DskPCaHhwgnBDazimQPuvAjz8LunCkjE7SEd8FssP8tg6Wzthl5CM24jLuiMVE43tdOvgNc2sR5EgxFs6CbYHVJiSUlztT3SnEWacHBnxWilvcqLTRQ5YZ/WJccuTYQqO7M2y/4CD9vLwKBgC8TfHuzRVKvu5R7zmBFrvO6p0z9o5TEmB1WKFptf4H6ko8kSplwCFxl3id6/LtsAaM7HmTJBjcWR7TYVCBFhlglxEvT71bOTvZh8tbvFEOFQWqwYCQWO0R8t/MoaHLj5Iw87+u3uQO1KVXjQgzY2CARf3Zv4fUeRT6BANzbFQ51AoGASWLq3dYrzUAKQRyvWwEl5chY87aFldEgK5u5GXqwjUw4RIV7ghbA6fc94MaZeFvGWH75P+iO8GBGHKRTG4cMzLTfPZ7SMCp6rT5FXj1jeWSxZ9hW8byM8sOycNGsETKzI/09tap7M/fxi1ah4jDv+PTONw+bX93cQwGI9jWqApsCgYBDPy91wBlHmKkRF7FtzoLsyYKU399CvDOp9qNGplRfd3BudC5zcqv32KAP77iCdE/4Av09UhsU6CMoIgSsAr8STu1KlJuoAro4njCXJctjjEbU1W9Ak4vXcg0dOEcwieRnpkVPJN2NKDCru2MdRzJfix7HXRaoPn1Dfp4/uuxBxg=="; //测试
    const privateKey = "MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCGmnOJ8MO6vymgyBX4OBr+ZTEF5Bv3y0cjL4JMIyt6+9ZOUmMKBbAe46W+LDMyP1W/bcfT2KpmptRM/JwKqnn3OskFYS7zj5k3K+bZFxuNgd3+WdTyfuY5gM0JO9hfwoERQRLQOXvGyFuJZ3sPlonBzlUQw6NJd+f8k8ZHCniZ+3tCs/vJIvXPcGLP7HWyQsJxMEfqCkutlFB/QXt+/YzLbwJkrv/fF9OPoygxuVX0YTXCk2RQM6P7jAsZszCJMy6DKsb4xYBGEnv/moFBGZO0fr1OY4XD6mAANyOJX2KUVPOrUEmGyXZxcm30fSbJe0vlEVljRp5FSQd0WGMxWpbrAgMBAAECggEAMc9eCiGqjKTJBtKELsK0WoefyIpNPzleLcsa8InjQTOIbptWQxMvxX6AiwtYNrPxS1GYrPB0vl/mGEcyh0/mqnJ6iR8ZfNAa5qu8VYGTlJ4GRGWBw+rpz0W2mkAnJeiYUoY+LAfZognqtcIxa+dNOwbbu8Rp3Hoewvz8VrWtQ22YPPBTqCtbwZi57VWmakmV10clJv+MPPoorigNsuj5DKqx/Kvcc+1WdgB7HMP2YDj/HYlVMq9Ib/iOaCVxnm263zbQ+F4mo0IGhXatNftF7TrYYbvK1f0Y6B4OGfkJGQFgkm/B2ZNd+rxDLaA72g9NrWTynhAGj7Fu9dUcTsYPIQKBgQD+c6ZgpublL+l0mKsFLI3T0oshOzFkkRwkiCzF1FYMFBx/P1BJ3jbcmP/WpE8ZdQ3QLEm52VdkBk785aZde5b5muBM24/Rq3UBAvXnwpXVVz0/sdsPsfOEGzQKu7CZSl7i6n0hwLessLLv5ytZGKjtLhSlxMOdncnBQZYB+8aFBQKBgQCHbB4xhyikmxap60ITVglgnRKMFK660DskPCaHhwgnBDazimQPuvAjz8LunCkjE7SEd8FssP8tg6Wzthl5CM24jLuiMVE43tdOvgNc2sR5EgxFs6CbYHVJiSUlztT3SnEWacHBnxWilvcqLTRQ5YZ/WJccuTYQqO7M2y/4CD9vLwKBgC8TfHuzRVKvu5R7zmBFrvO6p0z9o5TEmB1WKFptf4H6ko8kSplwCFxl3id6/LtsAaM7HmTJBjcWR7TYVCBFhlglxEvT71bOTvZh8tbvFEOFQWqwYCQWO0R8t/MoaHLj5Iw87+u3uQO1KVXjQgzY2CARf3Zv4fUeRT6BANzbFQ51AoGASWLq3dYrzUAKQRyvWwEl5chY87aFldEgK5u5GXqwjUw4RIV7ghbA6fc94MaZeFvGWH75P+iO8GBGHKRTG4cMzLTfPZ7SMCp6rT5FXj1jeWSxZ9hW8byM8sOycNGsETKzI/09tap7M/fxi1ah4jDv+PTONw+bX93cQwGI9jWqApsCgYBDPy91wBlHmKkRF7FtzoLsyYKU399CvDOp9qNGplRfd3BudC5zcqv32KAP77iCdE/4Av09UhsU6CMoIgSsAr8STu1KlJuoAro4njCXJctjjEbU1W9Ak4vXcg0dOEcwieRnpkVPJN2NKDCru2MdRzJfix7HXRaoPn1Dfp4/uuxBxg=="; //7557

    //代付请求地址
    //const remitUrl = "http://39.108.123.109:47590/gateway/order/remit/realtime-remittance"; // 测试地址
    const remitUrl = "https://api.shunwpay.com/gateway/order/remit/realtime-remittance"; // 生产环境

    //代付查询请求
    //const remitQueryUrl = "http://39.108.123.109:47590/gateway/query/remit/realtime-remittance"; // 测试地址
    const remitQueryUrl = "https://api.shunwpay.com/gateway/query/remit/realtime-remittance"; // 生产环境

    //余额查询请求
    //const balanceUrl = "http://39.108.123.109:47590/gateway/query/remit/balance"; // 测试地址
    const balanceUrl = "https://api.shunwpay.com/gateway/query/remit/balance"; // 生产环境

}