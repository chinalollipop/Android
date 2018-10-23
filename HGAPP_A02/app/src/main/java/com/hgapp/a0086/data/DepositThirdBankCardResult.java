package com.hgapp.a0086.data;

import android.os.Parcel;
import android.os.Parcelable;

import java.util.ArrayList;
import java.util.List;

public class DepositThirdBankCardResult {

    /**
     * status : 200
     * describe : success
     * timestamp : 20180709212308
     * data : [{"id":"79","thirdpay_code":"db","url":"https://pay.shuangfanwang.cn/dbpay.php","minCurrency":"100.00","maxCurrency":"3000.00","title":"得宝银行卡","userid":"81","bankList":[{"bankcode":"ABC","bankname":"农业银行"},{"bankcode":"ICBC","bankname":"工商银行"}]}]
     * sign : 2fac69512f46f43884079f479c10d270
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
         * id : 79
         * thirdpay_code : db
         * url : https://pay.shuangfanwang.cn/dbpay.php
         * minCurrency : 100.00
         * maxCurrency : 3000.00
         * title : 得宝银行卡
         * userid : 81
         * bankList : [{"bankcode":"ABC","bankname":"农业银行"},{"bankcode":"ICBC","bankname":"工商银行"}]
         */

        private String id;
        private String thirdpay_code;
        private String url;
        private String minCurrency;
        private String maxCurrency;
        private String title;
        private String userid;
        private List<BankListBean> bankList;

        public String getId() {
            return id;
        }

        public void setId(String id) {
            this.id = id;
        }

        public String getThirdpay_code() {
            return thirdpay_code;
        }

        public void setThirdpay_code(String thirdpay_code) {
            this.thirdpay_code = thirdpay_code;
        }

        public String getUrl() {
            return url;
        }

        public void setUrl(String url) {
            this.url = url;
        }

        public String getMinCurrency() {
            return minCurrency;
        }

        public void setMinCurrency(String minCurrency) {
            this.minCurrency = minCurrency;
        }

        public String getMaxCurrency() {
            return maxCurrency;
        }

        public void setMaxCurrency(String maxCurrency) {
            this.maxCurrency = maxCurrency;
        }

        public String getTitle() {
            return title;
        }

        public void setTitle(String title) {
            this.title = title;
        }

        public String getUserid() {
            return userid;
        }

        public void setUserid(String userid) {
            this.userid = userid;
        }

        public List<BankListBean> getBankList() {
            return bankList;
        }

        public void setBankList(List<BankListBean> bankList) {
            this.bankList = bankList;
        }

        public static class BankListBean {
            /**
             * bankcode : ABC
             * bankname : 农业银行
             */

            private String bankcode;
            private String bankname;

            public String getBankcode() {
                return bankcode;
            }

            public void setBankcode(String bankcode) {
                this.bankcode = bankcode;
            }

            public String getBankname() {
                return bankname;
            }

            public void setBankname(String bankname) {
                this.bankname = bankname;
            }
        }

        @Override
        public int describeContents() {
            return 0;
        }

        @Override
        public void writeToParcel(Parcel dest, int flags) {
            dest.writeString(this.id);
            dest.writeString(this.thirdpay_code);
            dest.writeString(this.url);
            dest.writeString(this.minCurrency);
            dest.writeString(this.maxCurrency);
            dest.writeString(this.title);
            dest.writeString(this.userid);
            dest.writeList(this.bankList);
        }

        public DataBean() {
        }

        protected DataBean(Parcel in) {
            this.id = in.readString();
            this.thirdpay_code = in.readString();
            this.url = in.readString();
            this.minCurrency = in.readString();
            this.maxCurrency = in.readString();
            this.title = in.readString();
            this.userid = in.readString();
            this.bankList = new ArrayList<BankListBean>();
            in.readList(this.bankList, BankListBean.class.getClassLoader());
        }

        public static final Parcelable.Creator<DataBean> CREATOR = new Parcelable.Creator<DataBean>() {
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
}
