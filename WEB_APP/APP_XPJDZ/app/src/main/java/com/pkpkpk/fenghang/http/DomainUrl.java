package com.pkpkpk.fenghang.http;

/**
 * Created by ak on 2017/9/25.
 */

public class DomainUrl {

    /**
     * domainUrl : https://3013777.com/m/
     * depositsUrl : https://3013777.com/m/member/GetDepositsBanks
     * withdrawUrl : https://3013777.com/m/member/withdraw
     */

    private String domainUrl;
    private String depositsUrl;
    private String withdrawUrl;

    public String getDomainUrl() {
        return domainUrl;
    }

    public void setDomainUrl(String domainUrl) {
        this.domainUrl = domainUrl;
    }

    public String getDepositsUrl() {
        return depositsUrl;
    }

    public void setDepositsUrl(String depositsUrl) {
        this.depositsUrl = depositsUrl;
    }

    public String getWithdrawUrl() {
        return withdrawUrl;
    }

    public void setWithdrawUrl(String withdrawUrl) {
        this.withdrawUrl = withdrawUrl;
    }
}
