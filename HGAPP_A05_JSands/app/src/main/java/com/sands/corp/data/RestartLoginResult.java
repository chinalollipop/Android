package com.sands.corp.data;

import java.util.List;

public class RestartLoginResult {

    /**
     * status : 401.1
     * describe : 你的登录信息已过期，请先登录!
     * timestamp : 20180925064102
     * data : []
     * sign :
     */

    private String status;
    private String describe;
    private String timestamp;
    private String sign;
    private List<?> data;

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public String getDescribe() {
        return describe;
    }

    public void setDescribe(String describe) {
        this.describe = describe;
    }

    public String getTimestamp() {
        return timestamp;
    }

    public void setTimestamp(String timestamp) {
        this.timestamp = timestamp;
    }

    public String getSign() {
        return sign;
    }

    public void setSign(String sign) {
        this.sign = sign;
    }

    public List<?> getData() {
        return data;
    }

    public void setData(List<?> data) {
        this.data = data;
    }
}
