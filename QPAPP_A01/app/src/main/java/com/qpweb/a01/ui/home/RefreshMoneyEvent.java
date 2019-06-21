package com.qpweb.a01.ui.home;

public class RefreshMoneyEvent {
    String bindPhone;
    public RefreshMoneyEvent(){}
    public RefreshMoneyEvent(String bindPhone) {
        this.bindPhone = bindPhone;
    }

    public String getBindPhone() {
        return bindPhone;
    }

    public void setBindPhone(String bindPhone) {
        this.bindPhone = bindPhone;
    }
}
