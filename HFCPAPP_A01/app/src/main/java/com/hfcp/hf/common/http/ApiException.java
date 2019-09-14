package com.hfcp.hf.common.http;

/**
 * Created by daniel on 2018/7/29.
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