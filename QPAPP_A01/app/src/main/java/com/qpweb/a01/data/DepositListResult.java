package com.qpweb.a01.data;

public class DepositListResult {

    /**
     * id : 6
     * bankid : 41
     * title : 线下支付宝一
     * minmoney : 0.00
     * api : /account/bank_type_ALISAOMA_api.php
     */

    private int id;
    private String bankid;
    private String title;
    private String minmoney;
    private String api;
    public boolean isCheck;

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getBankid() {
        return bankid;
    }

    public void setBankid(String bankid) {
        this.bankid = bankid;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getMinmoney() {
        return minmoney;
    }

    public void setMinmoney(String minmoney) {
        this.minmoney = minmoney;
    }

    public String getApi() {
        return api;
    }

    public void setApi(String api) {
        this.api = api;
    }

    public boolean isCheck() {
        return isCheck;
    }

    public void setCheck(boolean check) {
        isCheck = check;
    }
}
