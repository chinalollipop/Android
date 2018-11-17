package com.hgapp.a6668.data;

public class KYBalanceResult {
    private String ky_balance;
    private String hg_balance;
    private String ff_balance;

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

    public String getFf_balance() {
        return ff_balance;
    }

    public void setFf_balance(String ff_balance) {
        this.ff_balance = ff_balance;
    }

    @Override
    public String toString() {
        return "KYBalanceResult{" +
                "ky_balance='" + ky_balance + '\'' +
                ", hg_balance='" + hg_balance + '\'' +
                ", ff_balance='" + ff_balance + '\'' +
                '}';
    }
}
