package com.gmcp.gm.data;

import android.os.Parcel;
import android.os.Parcelable;

public class PersonBalanceResult implements Parcelable {
    /**
     * balance_ag : 0.00
     * balance_hg : 0.00
     * balance_cp : 0.00
     */

    private String balance_ag;
    private String balance_hg;
    private String balance_cp;

    public String getBalance_ag() {
        return balance_ag;
    }

    public void setBalance_ag(String balance_ag) {
        this.balance_ag = balance_ag;
    }

    public String getBalance_hg() {
        return balance_hg;
    }

    public void setBalance_hg(String balance_hg) {
        this.balance_hg = balance_hg;
    }

    public String getBalance_cp() {
        return balance_cp;
    }

    public void setBalance_cp(String balance_cp) {
        this.balance_cp = balance_cp;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.balance_ag);
        dest.writeString(this.balance_hg);
        dest.writeString(this.balance_cp);
    }

    public PersonBalanceResult() {
    }

    protected PersonBalanceResult(Parcel in) {
        this.balance_ag = in.readString();
        this.balance_hg = in.readString();
        this.balance_cp = in.readString();
    }

    public static final Creator<PersonBalanceResult> CREATOR = new Creator<PersonBalanceResult>() {
        @Override
        public PersonBalanceResult createFromParcel(Parcel source) {
            return new PersonBalanceResult(source);
        }

        @Override
        public PersonBalanceResult[] newArray(int size) {
            return new PersonBalanceResult[size];
        }
    };
}
