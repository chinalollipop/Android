package com.sunapp.bloc.data;

import android.os.Parcel;
import android.os.Parcelable;

import java.util.ArrayList;
import java.util.List;

public class DepositAliPayQCCodeResult implements Parcelable {
    /**
     * status : 200
     * describe : success
     * timestamp : 20180709043226
     * data : [{"id":"14","bank_user":"扫一扫支付最低100元最高5000元","photo_name":"http://hg6668app.com/zwgr.png"}]
     * sign : d09c44f00534a9e000c1ea87caefa224
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
         * id : 14
         * bank_user : 扫一扫支付最低100元最高5000元
         * photo_name : http://hg6668app.com/zwgr.png
         */

        private String id;
        private String bank_user;
        private String photo_name;
        private String notice;

        public String getId() {
            return id;
        }

        public void setId(String id) {
            this.id = id;
        }

        public String getBank_user() {
            return bank_user;
        }

        public void setBank_user(String bank_user) {
            this.bank_user = bank_user;
        }

        public String getPhoto_name() {
            return photo_name;
        }

        public void setPhoto_name(String photo_name) {
            this.photo_name = photo_name;
        }

        public String getNotice() {
            return notice;
        }

        public void setNotice(String notice) {
            this.notice = notice;
        }

        @Override
        public int describeContents() {
            return 0;
        }

        @Override
        public void writeToParcel(Parcel dest, int flags) {
            dest.writeString(this.id);
            dest.writeString(this.bank_user);
            dest.writeString(this.photo_name);
            dest.writeString(this.notice);
        }

        public DataBean() {
        }

        protected DataBean(Parcel in) {
            this.id = in.readString();
            this.bank_user = in.readString();
            this.photo_name = in.readString();
            this.notice = in.readString();
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

    public DepositAliPayQCCodeResult() {
    }

    protected DepositAliPayQCCodeResult(Parcel in) {
        this.status = in.readString();
        this.describe = in.readString();
        this.timestamp = in.readString();
        this.sign = in.readString();
        this.data = new ArrayList<DataBean>();
        in.readList(this.data, DataBean.class.getClassLoader());
    }

    public static final Parcelable.Creator<DepositAliPayQCCodeResult> CREATOR = new Parcelable.Creator<DepositAliPayQCCodeResult>() {
        @Override
        public DepositAliPayQCCodeResult createFromParcel(Parcel source) {
            return new DepositAliPayQCCodeResult(source);
        }

        @Override
        public DepositAliPayQCCodeResult[] newArray(int size) {
            return new DepositAliPayQCCodeResult[size];
        }
    };
}
