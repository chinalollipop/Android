<?php
class Config{

    const nerchNo = "4f044404a0";     // 测试:4b284a5186   6668:4f044404a0

    //支付请求url  没用
    //const payurl = "http://test.qmz918.com/api/cash/placeCash";

    //支付订单查询url 没用
    //const payQuery ="http://apidemo.jumingpay.com.cn/api/payOrder/query";

    //配置支付平台公钥
    // 测试
    //const publicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnRxB3lsiSCNkXq2ziH80QIdCqP5IcEH5hg7bJa2SG3oXYr1bYEP7uytu5P1Tv172Dt73nRzQWMZFUvcxbRDM2msdIZhLWRM2PXc1vky9+8xztY0TrseH3m+gWcowvk3xcdWpguTqLPOol9hzTVL+Ye4TVLbBTEL5DXn8wjBvcmsc/PdLCdIlpUr5RGJU6HwpZdOnLVs5R6ZeHfc4yxNmI4xvT6Y87bErcdLWduCh+0t8j9KADR04IzkXcT+G89Ucco/ZDZNu9kkPJANBfCuJPNhemKu5OlwXJLIMergsAOFrVGBL7MggRz2TohRSzbPGpxZGro7BYZgvjtzdkCxm5QIDAQAB";
    // 6668
    const publicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqlP68zlvOmzsnKXuecu0RF+XfqKCD69PNlQahomd9YefkiA4iIv6i4Y9atZfgQNddUE8qz3+VUUBs/famMQBRwgm4IU36Owb162El33seXXahtyJZqDQFO6X+PxWR7WY+e/rvtPr2aUdA2UuNjg5JF130bMmnXCq2H/ru5/DXFA9iqPxvYKiWj6ku62589H0esS+MgW3CuFz/unYTwQJHvLU7WesoCsA01OFg8tVzujSmSXDf7MYeoJJ82MatI0mThSRvidxCDOXC80ATT8vR1qJCVh8BWedcgFaAKkE3gjhcMUL35yL57x7YLKyjqMrBeDAoHPU4Hk281AwckdA5wIDAQAB";

    //配置支付私钥(商户私钥)
    // 测试
    //const privateKey = "MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCdHEHeWyJII2RerbOIfzRAh0Ko/khwQfmGDtslrZIbehdivVtgQ/u7K27k/VO/XvYO3vedHNBYxkVS9zFtEMzaax0hmEtZEzY9dzW+TL37zHO1jROux4feb6BZyjC+TfFx1amC5Oos86iX2HNNUv5h7hNUtsFMQvkNefzCMG9yaxz890sJ0iWlSvlEYlTofCll06ctWzlHpl4d9zjLE2YjjG9PpjztsStx0tZ24KH7S3yP0oANHTgjORdxP4bz1Rxyj9kNk272SQ8kA0F8K4k82F6Yq7k6XBcksgx6uCwA4WtUYEvsyCBHPZOiFFLNs8anFkaujsFhmC+O3N2QLGblAgMBAAECggEAKuCxYVwB6SovlF9XpiMBQbMokDKF1o1K6jlXudq7C2CwzTPcolMrepOJ+ljg6FOkV76mWWypt/C0rsXj6V4yalHda3PC7JZ/sRq9wifzmarc0WmlO4gdHqncW2UBFI71HBox3xVWi9ob4wUhwrKp1lRBVldiPcvxaKooP180q0b+8yfPasunvPxnSlQVCMheMniyzMqN4nRKF4mYzek550aK5AGOCfIZr3RIvB2hzGxNhGQtPVeOU4i+aW/BDSw+Id3QIjYl2OsW85OQnG3GY4vRXgSuYggU6Sr+L7WyQpSbUUrfhXxlDfVXsMpZ5JlSOLsTfvJenXHpQOBqw7H3wQKBgQDKmsTOqduMsuONeo8KUsNMBfqqp0zsleKtyrEyYRELL32nP5L8p0nUUaeD5T95iSHXKgz0keCh+C8fN0HCLcIu51E/npMN4pXxqxb8sdgR4k5Yqw9S3xY5WY5Sw7sbnIbb91i+JLrhKQRXQTc80/EgpiZQKyZ+QiPlz+v085I6LQKBgQDGhBubDZAjZoIJ/gw1XF1c49tQxsmz4DFf8lnqQN+iigWIctPff5/hpuk5FhNY6JX+0H4oVCaxUX8itF0r130NsGbKNiWQLf5JxoUZTtQ94gWWYEGfMnIqYMvaidjwU8RcudTYk0uzP2UnG4gOv5NRf+5ioeVG7shwEIAg3BVqmQKBgAPmww9vueiVsUgSKhr6yQP3wYHzwslzgW/zTUI5GEjs2zCTStNOrV9HS0CA8531hA9Oof07qeW0j8O8HqoMk4avsaLV/OLxkA2dS84F5rFBeFzAvoTMAvOLw+/YEQxREU+/DZhrwKWBUrITcWrccfI9ANPeYNlhkKtmO5b18cTpAoGAAdg+bW1t1nZgZPlgYaqPD4rqgdCnFS3TJ6IX5c5ehaMktATlJSGJec5UQnyLB3t50VlcosFNbr5kIQ1uBDdHaTYnbl+cb7+Nql/W4spRvJV6GdChK3qLhwtJOamoQ2tz2qy7ZpvPy0WeigN+eyyakNpQe8gnWkZjxG7S0ftZk5kCgYEAltsc+YsZK6GfeFHODUy4aybKBhYhHdyhY1X8PoHersZ5YsT9Zi9F16E35EpWU7bUD+E8gsHcjME3XSCv0dtmidyw8Dv2UIJfOgCrIvWP2arwj7LS/dniaRgCUf/2Jvn5QqPf3ser4PTG4M+IoxcEbOwXqRvxM+iHRlp9XSgN/lc=";
    // 6668
    const privateKey = "MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDnI2a91zhoJzZRYJ7Ka9b2tcanoH/EqamyPQqrMD4eFCmem6HWPOcUAGDXpD5vZqEVYRLpUhr4uVyRFssV+Kb8nLlRHgmQkofRNTqwPSzg0v4lhWBnKQeKoEC4ljVNDupmvHekp371k/XSC9WNdIMtGvyaPYn+lLHlaFVTR7CcK6pON3e4ZK9Ov6pexc0bLh0B25qW/y/iGHqGgS2qFnDgwR1NSoB63tHesGeu1jfBVLnyaybDCljWvV28U2tGxLBNf6kgp4yx5kZSVs0TnQ3KN+KxYlOwajVzOskkwjoROgJBJRoejgMbRIIEo1xbEi143DZgPNglotSnp6HFyjqdAgMBAAECggEAeyAZl+Ew9BKHVdwxWkUN8sgsNZlC1qFGe2MW7tA5Fs7IyOvAzx4MPlRhQh4FAFTcVEYsy8toALzFOai5sE+PrJtUAIkOsUvNO72uMLm10WGz7orZKrrpgIgTUgD6Db13UhjtZvGuqOUTmruhG8RIKFvq2cMUYmAVT84/Pai/xMBekQi7ax9CtEx0WHetE2lI3QeKD00YyFEu1OBGC/fIs36OSBJFePRpjIiO0RWvg8B6k0HCzTszhAZaLxLtlyg9/mgmtJatpFAJV4OgKENR4j7dl34oLaDIJWIAeY3nG9x1uyAkzAf2yUHnFkB8LmbWl98Dfem3e4i3gwdfb2mDBQKBgQD9AvPZpv8LSlFqyvIF1soy9rSc+13QWsLolFUPR1y9KFOxSvu4ahgugzIh+IkOsNLv2RIXAO5izQgGo5ndeCvrLsVXPWf2srsgMCZL/eQqsLSUsWqjGxB7NJTl0lG2+SvGRa0OKsiRLQQj3vLxnhF2vOlE6/fprrPV6YlhqmJvLwKBgQDp3k8p21GqxE9x/8/WLKLjL1ofXrMraRQuZqe7Agq7vZhjH83Xspt1RxPcj3wJEzSfYvm0JAD8LmdAE0wxazUZhG88tktxC9H5x6onFM1Wa6iDX6glAKsSyeY4iLFu2XcAvhm9CK/BbOS5v8O2y9oRvO1aUq7Sz20KX+Is9lwf8wKBgFlnT6+bWQguTLLF39u8WFrF3nCSSkYzrCfvMldhyh11QI2nh2dU5hKZfX+PiS7NwNvMAp5qT9JNzQY7jUU7D5ndPxJiunfsFD99hCraHmQPaOAaCSp1h89hdzP2q7VSOuum903IRHM4Svw57fL+/gDNJEiccU/70cRG76q5QGs1AoGBAMybU5UiLVQCxiLNlQ8jJCsxDV7uokBzgVWLOGnTQoK4QG9Qru1sdLfpRIYt/m2OIPblR+ODsFzxWCHmAD4oNl+pzwxFzMENf1pRhhsFBxT5wYeWk+wzpngcrc9+QAXkOkorByesRuAqHf4ouNjfhl0IdnwUZKFJGnP+KUn/C+BjAoGBAO8MYjNGTirdhAfBhSt6siJrz66e7z1SonJZURkSf+OBXi8TxWPk7XMPEUSKlDdTpkqkBQQ0Q/i+qs8tL3CeSow4H3WzVIRLL8FtTOaAhPgQnwaDNYA1ju+ksc7x1vnv5yJvoZ8Dk5vYJAY0qqMu/mVw4kJR3SjrZYJKsU9kij7c";

    //配置代付请求url
    //const remiturl = "http://test.qmz918.com/api/cash/placeCash";   //测试
    const remiturl = "https://api.bixin88.com/api/cash/placeCash";   //正式

    //配置代付查询url
    const remitQuery = "http://apidemo.jumingpay.com.cn/api/cash/queryCash";


}