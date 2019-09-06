package com.hfcp.hf.data;

public class AgGamePayResult {

    /**
     * errno : 7503
     * error : 代理禁止游戏！
     * data : null
     * sign : 59a3dbf13a67485ac285b2b2904f5dbf
     */

    private String errno;
    private String error;
    private Object data;
    private String sign;

    public String getErrno() {
        return errno;
    }

    public void setErrno(String errno) {
        this.errno = errno;
    }

    public String getError() {
        return error;
    }

    public void setError(String error) {
        this.error = error;
    }

    public Object getData() {
        return data;
    }

    public void setData(Object data) {
        this.data = data;
    }

    public String getSign() {
        return sign;
    }

    public void setSign(String sign) {
        this.sign = sign;
    }
}
