package com.hgapp.a6668.data;

public class KYBalanceResult {
    private String ky_balance;
    private String hg_balance;
    private String ff_balance;
    private String vg_balance;
    private String ly_balance;

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

    public String getVg_balance() {
        return vg_balance;
    }

    public void setVg_balance(String vg_balance) {
        this.vg_balance = vg_balance;
    }

    public String getLy_balance() {
        return ly_balance;
    }

    public void setLy_balance(String ly_balance) {
        this.ly_balance = ly_balance;
    }

    @Override
    public String toString() {
        return "KYBalanceResult{" +
                "ky_balance='" + ky_balance + '\'' +
                ", hg_balance='" + hg_balance + '\'' +
                ", ff_balance='" + ff_balance + '\'' +
                ", vg_balance='" + vg_balance + '\'' +
                ", ly_balance='" + ly_balance + '\'' +
                '}';
    }
}
