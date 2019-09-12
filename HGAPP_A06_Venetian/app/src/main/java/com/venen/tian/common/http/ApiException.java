package com.venen.tian.common.http;

/**
 * Created by ak on 2017/7/29.
 */

public class ApiException extends Exception {
    private int resultCode;
    private String errorMsg;
    public ApiException(int resultCode, String errorMsg) {
        this.resultCode = resultCode;
        this.errorMsg = errorMsg;
    }
    public int getResultCode() {
        return this.resultCode;
    }
    @Override
    public String getMessage() {
        return errorMsg;
    }
}
