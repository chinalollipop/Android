package com.hgapp.a6668.data;

public class KYBalanceResult {
    private String ky_balance;
    private String hg_balance;

    public String getKy_balance() {
        return ky_balance;
    }

    public void setKy_balance(String ky_balance) {
        this.ky_balance = ky_balance;
    }

    public String getHg_balance() {
        return hg_balance;
    }

    public void setHg_balance(String hg_balance) {
        this.hg_balance = hg_balance;
    }

    @Override
    public String toString() {
        return "KYBalanceResult{" +
                "ky_balance='" + ky_balance + '\'' +
                ", hg_balance='" + hg_balance + '\'' +
                '}';
    }
}
