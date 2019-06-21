package com.qpweb.a01.data;

import android.os.Parcel;
import android.os.Parcelable;

import java.util.ArrayList;
import java.util.List;

public class DepositBankCordListResult implements Parcelable {


    /**
     * status : 200
     * describe : success
     * timestamp : 20180709222131
     * data : [{"bank_account":"6228480789741******","bank_name":"农业银行","bank_user":"关元","id":"11"},{"bank_account":"621483280*******","bank_name":"招商银行","bank_user":"何莎莉","id":"15"},{"bank_account":"621700380******","bank_name":"建设银行","bank_user":"","id":"17"}]
     * sign : 8f6aa8358b9a5b1a71e05b1976e963c2
     */

    private String status;
    private String describe;
    private String timestamp;
    private String sign;
    private List<DataBean> data;

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

    public String getSign() {
        return sign;
    }

    public void setSign(String sign) {
        this.sign = sign;
    }

    public List<DataBean> getData() {
        return data;
    }

    public void setData(List<DataBean> data) {
        this.data = data;
    }

    public static class DataBean implements Parcelable {
        /**
         * bank_account : 6228480789741******
         * bank_name : 农业银行
         * bank_user : 关元
         * id : 11
         */

        private String bank_account;
        private String bank_name;
        private String bank_user;
        private String bank_addres;
        private String id;

        public String getBank_account() {
            return bank_account;
        }

        public void setBank_account(String bank_account) {
            this.bank_account = bank_account;
        }

        public String getBank_name() {
            return bank_name;
        }

        public void setBank_name(String bank_name) {
            this.bank_name = bank_name;
        }

        public String getBank_user() {
            return bank_user;
        }

        public void setBank_user(String bank_user) {
            this.bank_user = bank_user;
        }

        public String getBank_addres() {
            return bank_addres;
        }

        public void setBank_addres(String bank_addres) {
            this.bank_addres = bank_addres;
        }

        public String getId() {
            return id;
        }

        public void setId(String id) {
            this.id = id;
        }

        @Override
        public int describeContents() {
            return 0;
        }

        @Override
        public void writeToParcel(Parcel dest, int flags) {
            dest.writeString(this.bank_account);
            dest.writeString(this.bank_name);
            dest.writeString(this.bank_user);
            dest.writeString(this.bank_addres);
            dest.writeString(this.id);
        }

        public DataBean() {
        }

        protected DataBean(Parcel in) {
            this.bank_account = in.readString();
            this.bank_name = in.readString();
            this.bank_user = in.readString();
            this.bank_addres = in.readString();
            this.id = in.readString();
        }

        public static final Creator<DataBean> CREATOR = new Creator<DataBean>() {
            @Override
            public DataBean createFromParcel(Parcel source) {
                return new DataBean(source);
            }

            @Override
            public DataBean[] newArray(int size) {
                return new DataBean[size];
            }
        };
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.status);
        dest.writeString(this.describe);
        dest.writeString(this.timestamp);
        dest.writeString(this.sign);
        dest.writeList(this.data);
    }

    public DepositBankCordListResult() {
    }

    protected DepositBankCordListResult(Parcel in) {
        this.status = in.readString();
        this.describe = in.readString();
        this.timestamp = in.readString();
        this.sign = in.readString();
        this.data = new ArrayList<DataBean>();
        in.readList(this.data, DataBean.class.getClassLoader());
    }

    public static final Creator<DepositBankCordListResult> CREATOR = new Creator<DepositBankCordListResult>() {
        @Override
        public DepositBankCordListResult createFromParcel(Parcel source) {
            return new DepositBankCordListResult(source);
        }

        @Override
        public DepositBankCordListResult[] newArray(int size) {
            return new DepositBankCordListResult[size];
        }
    };
}
