package com.gmcp.gm.ui.home.cplist.events;

public class ServiceEvent {
    public ServiceEvent() {
    }

    public ServiceEvent(boolean success, String msg, int code) {
        this.success = success;
        this.msg = msg;
        this.code = code;
    }

    public ServiceEvent(String msg) {
        this.msg = msg;
    }

    /**
     * success : false
     * msg : 游戏已经封盘,请稍候再下注!
     * code : 500
     */

    private boolean success;
    private String msg;
    private int code;

    public boolean isSuccess() {
        return success;
    }

    public void setSuccess(boolean success) {
        this.success = success;
    }

    public String getMsg() {
        return msg;
    }

    public void setMsg(String msg) {
        this.msg = msg;
    }

    public int getCode() {
        return code;
    }

    public void setCode(int code) {
        this.code = code;
    }
}
