package com.hgapp.betnhg.data;

import android.graphics.drawable.Drawable;

/**
 * Created by ak on 2018/8/30.
 * 银行存款银行实体类
 */

public class StartAppList {

    public StartAppList(int payId, Drawable payIcon, String payName, String payQuota){
        this.payId = payId;
        this.payIcon = payIcon;
        this.payName = payName;
        this.payQuota = payQuota;
    }
    public StartAppList(){

    }
    public StartAppList(int payId, Drawable payIcon, String payName, String packageName, String payQuota){
        this.payId = payId;
        this.payIcon = payIcon;
        this.payName = payName;
        this.payQuota = payQuota;
        this.packageName = packageName;
    }

    private String packageName;

    public String getPackageName() {
        return packageName;
    }

    public void setPackageName(String packageName) {
        this.packageName = packageName;
    }

    private Drawable payIcon;
    //银行卡的id号
    private int payId;

    //支付名称
    private String payName ;
    //支付限额
    private String payQuota;

    public Drawable getPayIcon() {
        return payIcon;
    }

    public void setPayIcon(Drawable payIcon) {
        this.payIcon = payIcon;
    }

    public int getPayId() {
        return payId;
    }

    public void setPayId(int payId) {
        this.payId = payId;
    }
    public String getPayName() {
        return payName;
    }

    public void setPayName(String payName) {
        this.payName = payName;
    }

    public String getPayQuota() {
        return payQuota;
    }

    public void setPayQuota(String payQuota) {
        this.payQuota = payQuota;
    }
}
