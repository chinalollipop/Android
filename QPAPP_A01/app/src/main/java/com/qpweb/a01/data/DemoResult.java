package com.qpweb.a01.data;

import android.os.Parcel;
import android.os.Parcelable;

public class DemoResult {

    /**
     * status : 200
     * describe : 登录成功！
     * timestamp : 20190604174816
     * data : {"Oid":"158d12466930a920722era9","UserName":"demoguest29","Agents":"demoguest","Money":0,"Alias":"试玩账号","isTest":"0","isBindCard":"0","LoginTime":"2019-06-04 17:48:16","lastLoginTime":"2019-06-04 05:21:43","promotion_code":null}
     * sign :
     */

    private String status;
    private String describe;
    private String timestamp;
    private LoginResult data;
    private String sign;

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

    public LoginResult getData() {
        return data;
    }

    public void setData(LoginResult data) {
        this.data = data;
    }

    public String getSign() {
        return sign;
    }

    public void setSign(String sign) {
        this.sign = sign;
    }


}
