package com.hfcp.hf.data;

public class CPQuickBetMothedResult {

    /**
     * success : true
     * msg : 保存快捷投注成功！
     * code : 200
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
