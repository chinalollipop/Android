package com.cfcp.a01.data;

public class ReLoginResult {

    /**
     * errno : 3004
     * error : 请登录后访问
     * data : null
     */

    private int errno;
    private String error;
    private Object data;

    public int getErrno() {
        return errno;
    }

    public void setErrno(int errno) {
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
}
