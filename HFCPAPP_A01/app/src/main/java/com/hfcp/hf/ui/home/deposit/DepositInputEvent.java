package com.hfcp.hf.ui.home.deposit;

public class DepositInputEvent {
    public String money;
    public boolean isCheck;

    public DepositInputEvent(String money, boolean isCheck) {
        this.money = money;
        this.isCheck = isCheck;
    }
}
