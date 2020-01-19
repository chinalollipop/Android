package com.hgapp.m8.homepage.cplist;

public class CPOrderContentResult {
    private String orderId;
    private String orderName;
    private String fullName;
    private String orderState;
    private String orderSX ="";
    private boolean isChecked;
    private String isQuick = "0";

    public String getOrderId() {
        return orderId;
    }

    public void setOrderId(String orderId) {
        this.orderId = orderId;
    }

    public String getOrderName() {
        return orderName;
    }

    public void setOrderName(String orderName) {
        this.orderName = orderName;
    }

    public String getFullName() {
        return fullName;
    }

    public void setFullName(String fullName) {
        this.fullName = fullName;
    }

    public String getOrderState() {
        return orderState;
    }

    public void setOrderState(String orderState) {
        this.orderState = orderState;
    }

    public String getOrderSX() {
        return orderSX;
    }

    public void setOrderSX(String orderSX) {
        this.orderSX = orderSX;
    }

    public boolean isChecked() {
        return isChecked;
    }

    public void setChecked(boolean checked) {
        isChecked = checked;
    }

    public String getIsQuick() {
        return isQuick;
    }

    public void setIsQuick(String isQuick) {
        this.isQuick = isQuick;
    }
}
