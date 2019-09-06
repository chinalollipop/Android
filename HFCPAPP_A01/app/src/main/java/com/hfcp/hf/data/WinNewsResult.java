package com.hfcp.hf.data;

public class WinNewsResult {

    /**
     * status : 200
     * message : success
     * data : 恭喜玩家 ****PwFru，在捕鱼王中，中五等奖，奖金 17164.64 ！
     */

    private int status;
    private String message;
    private String data;

    public int getStatus() {
        return status;
    }

    public void setStatus(int status) {
        this.status = status;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public String getData() {
        return data;
    }

    public void setData(String data) {
        this.data = data;
    }
}
