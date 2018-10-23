package com.hgapp.a0086.homepage.handicap.betapi;

public class PrepareBet {

    private String rtype;
    private String wtype;
    private String order_type;
    private String order_method;
    private String error_flag;
    private String type;
    private String odd_f_type;
    private String appRefer;
    private String gid;

    public PrepareBet(String rtype, String wtype, String order_type, String order_method, String error_flag, String type, String odd_f_type, String appRefer, String gid) {
        this.rtype = rtype;
        this.wtype = wtype;
        this.order_type = order_type;
        this.order_method = order_method;
        this.error_flag = error_flag;
        this.type = type;
        this.odd_f_type = odd_f_type;
        this.appRefer = appRefer;
        this.gid = gid;
    }

    public String getRtype() {
        return rtype;
    }

    public void setRtype(String rtype) {
        this.rtype = rtype;
    }

    public String getWtype() {
        return wtype;
    }

    public void setWtype(String wtype) {
        this.wtype = wtype;
    }

    public String getOrder_type() {
        return order_type;
    }

    public void setOrder_type(String order_type) {
        this.order_type = order_type;
    }

    public String getOrder_method() {
        return order_method;
    }

    public void setOrder_method(String order_method) {
        this.order_method = order_method;
    }

    public String getError_flag() {
        return error_flag;
    }

    public void setError_flag(String error_flag) {
        this.error_flag = error_flag;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getOdd_f_type() {
        return odd_f_type;
    }

    public void setOdd_f_type(String odd_f_type) {
        this.odd_f_type = odd_f_type;
    }

    public String getAppRefer() {
        return appRefer;
    }

    public void setAppRefer(String appRefer) {
        this.appRefer = appRefer;
    }

    public String getGid() {
        return gid;
    }

    public void setGid(String gid) {
        this.gid = gid;
    }
/**
     rtype = "",
     wtype = "OU",
     order_type = "",
     order_method = "FT_ou",
     error_flag = "",
     type = "H",
     odd_f_type = "H",
     appRefer = "13",
     gid = "3346848",
     */
}
