package com.hgapp.bet365.data;

import android.os.Parcel;
import android.os.Parcelable;

import com.contrarywind.interfaces.IPickerViewData;

import java.util.List;

public class DepositThirdQQPayResult{

    /**
     * status : 200
     * describe : success
     * timestamp : 20180709062934
     * data : [{"id":"80","thirdpay_code":"rx","url":"http://pay1.pay6668a.com/rxpay.php","minCurrency":"100.00","maxCurrency":"3000.00","title":"仁信微信","userid":"9985"}]
     * sign : 87f895ea74c75b391c72ac6be13a8557
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

    public static class DataBean implements Parcelable , IPickerViewData {
        /**
         * id : 80
         * thirdpay_code : rx
         * url : http://pay1.pay6668a.com/rxpay.php
         * minCurrency : 100.00
         * maxCurrency : 3000.00
         * title : 仁信微信
         * userid : 9985
         */

        private String id;
        private String thirdpay_code;
        private String url;
        private String minCurrency;
        private String maxCurrency;
        private String title;
        private String userid;

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

        @Override
        public String getPickerViewText() {
            return title;
        }
    }
}
