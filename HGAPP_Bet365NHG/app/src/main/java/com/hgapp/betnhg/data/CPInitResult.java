package com.hgapp.betnhg.data;

public class CPInitResult {
    /**
     * token : bac6d071cd84eebd14a48790d7238c73
     * serverTime : 2018-11-26 12:03:53
     * userId : 3994
     * userName : seven02
     * fullName : seven02
     * loginTime : 2018-11-26 12:03:53
     * lastLoginTime : 2018-11-26 12:03:50
     * money : 0
     * email :
     * hasFundPwd : true
     * testFlag : 0
     * updatePw : 0
     * updatePayPw : 0
     * websocketIp : ws://pk10.hg01455.com
     * websocketPort : 9501
     */

    private String token;
    private String serverTime;
    private String userId;
    private String userName;
    private String fullName;
    private String loginTime;
    private String lastLoginTime;
    private int money;
    private String email;
    private boolean hasFundPwd;
    private String testFlag;
    private int updatePw;
    private int updatePayPw;
    private String websocketIp;
    private String websocketPort;

    public String getToken() {
        return token;
    }

    public void setToken(String token) {
        this.token = token;
    }

    public String getServerTime() {
        return serverTime;
    }

    public void setServerTime(String serverTime) {
        this.serverTime = serverTime;
    }

    public String getUserId() {
        return userId;
    }

    public void setUserId(String userId) {
        this.userId = userId;
    }

    public String getUserName() {
        return userName;
    }

    public void setUserName(String userName) {
        this.userName = userName;
    }

    public String getFullName() {
        return fullName;
    }

    public void setFullName(String fullName) {
        this.fullName = fullName;
    }

    public String getLoginTime() {
        return loginTime;
    }

    public void setLoginTime(String loginTime) {
        this.loginTime = loginTime;
    }

    public String getLastLoginTime() {
        return lastLoginTime;
    }

    public void setLastLoginTime(String lastLoginTime) {
        this.lastLoginTime = lastLoginTime;
    }

    public int getMoney() {
        return money;
    }

    public void setMoney(int money) {
        this.money = money;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public boolean isHasFundPwd() {
        return hasFundPwd;
    }

    public void setHasFundPwd(boolean hasFundPwd) {
        this.hasFundPwd = hasFundPwd;
    }

    public String getTestFlag() {
        return testFlag;
    }

    public void setTestFlag(String testFlag) {
        this.testFlag = testFlag;
    }

    public int getUpdatePw() {
        return updatePw;
    }

    public void setUpdatePw(int updatePw) {
        this.updatePw = updatePw;
    }

    public int getUpdatePayPw() {
        return updatePayPw;
    }

    public void setUpdatePayPw(int updatePayPw) {
        this.updatePayPw = updatePayPw;
    }

    public String getWebsocketIp() {
        return websocketIp;
    }

    public void setWebsocketIp(String websocketIp) {
        this.websocketIp = websocketIp;
    }

    public String getWebsocketPort() {
        return websocketPort;
    }

    public void setWebsocketPort(String websocketPort) {
        this.websocketPort = websocketPort;
    }
}
