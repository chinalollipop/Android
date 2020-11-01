package com.hgapp.betnew.data;

import android.os.Parcel;
import android.os.Parcelable;

import java.util.List;

public class BetResult {

    /**
     * status : 200
     * describe : 投注成功
     * timestamp : 20180826061109
     * data : [{"caption":"篮球单式让球交易单","Order_Bet_success":"交易成功单号：","order":"RAA808269841010054514","s_sleague":"美国职业美式足球季前赛","M_Date":"08-26","s_mb_team":"水牛城比尔","Sign":"1","s_tg_team":"辛辛那提孟加拉虎","s_m_place":"辛辛那提孟加拉虎","w_m_rate":"0.92","gold":"23","order_bet_amount":21.16,"havemoney":95115.14}]
     * sign : a4361c0b83497bdae4ff1b6e349cdf26
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
         * caption : 篮球单式让球交易单
         * Order_Bet_success : 交易成功单号：
         * order : RAA808269841010054514
         * s_sleague : 美国职业美式足球季前赛
         * M_Date : 08-26
         * s_mb_team : 水牛城比尔
         * Sign : 1
         * s_tg_team : 辛辛那提孟加拉虎
         * s_m_place : 辛辛那提孟加拉虎
         * w_m_rate : 0.92
         * gold : 23
         * order_bet_amount : 21.16
         * havemoney : 95115.14
         */

        private String caption;
        private String Order_Bet_success;
        private String order;
        private String inball;
        private String s_sleague;
        private String M_Date;
        private String s_mb_team;
        private String Sign;
        private String s_tg_team;
        private String s_m_place;
        private String w_m_rate;
        private String gold;
        private double order_bet_amount;
        private String havemoney;

        public String getCaption() {
            return caption;
        }

        public void setCaption(String caption) {
            this.caption = caption;
        }

        public String getOrder_Bet_success() {
            return Order_Bet_success;
        }

        public void setOrder_Bet_success(String Order_Bet_success) {
            this.Order_Bet_success = Order_Bet_success;
        }

        public String getOrder() {
            return order;
        }

        public void setOrder(String order) {
            this.order = order;
        }

        public String getInball() {
            return inball;
        }

        public void setInball(String inball) {
            this.inball = inball;
        }

        public String getS_sleague() {
            return s_sleague;
        }

        public void setS_sleague(String s_sleague) {
            this.s_sleague = s_sleague;
        }

        public String getM_Date() {
            return M_Date;
        }

        public void setM_Date(String M_Date) {
            this.M_Date = M_Date;
        }

        public String getS_mb_team() {
            return s_mb_team;
        }

        public void setS_mb_team(String s_mb_team) {
            this.s_mb_team = s_mb_team;
        }

        public String getSign() {
            return Sign;
        }

        public void setSign(String Sign) {
            this.Sign = Sign;
        }

        public String getS_tg_team() {
            return s_tg_team;
        }

        public void setS_tg_team(String s_tg_team) {
            this.s_tg_team = s_tg_team;
        }

        public String getS_m_place() {
            return s_m_place;
        }

        public void setS_m_place(String s_m_place) {
            this.s_m_place = s_m_place;
        }

        public String getW_m_rate() {
            return w_m_rate;
        }

        public void setW_m_rate(String w_m_rate) {
            this.w_m_rate = w_m_rate;
        }

        public String getGold() {
            return gold;
        }

        public void setGold(String gold) {
            this.gold = gold;
        }

        public double getOrder_bet_amount() {
            return order_bet_amount;
        }

        public void setOrder_bet_amount(double order_bet_amount) {
            this.order_bet_amount = order_bet_amount;
        }

        public String getHavemoney() {
            return havemoney;
        }

        public void setHavemoney(String havemoney) {
            this.havemoney = havemoney;
        }

        @Override
        public int describeContents() {
            return 0;
        }

        @Override
        public void writeToParcel(Parcel dest, int flags) {
            dest.writeString(this.caption);
            dest.writeString(this.Order_Bet_success);
            dest.writeString(this.order);
            dest.writeString(this.inball);
            dest.writeString(this.s_sleague);
            dest.writeString(this.M_Date);
            dest.writeString(this.s_mb_team);
            dest.writeString(this.Sign);
            dest.writeString(this.s_tg_team);
            dest.writeString(this.s_m_place);
            dest.writeString(this.w_m_rate);
            dest.writeString(this.gold);
            dest.writeDouble(this.order_bet_amount);
            dest.writeString(this.havemoney);
        }

        public DataBean() {
        }

        protected DataBean(Parcel in) {
            this.caption = in.readString();
            this.Order_Bet_success = in.readString();
            this.order = in.readString();
            this.inball = in.readString();
            this.s_sleague = in.readString();
            this.M_Date = in.readString();
            this.s_mb_team = in.readString();
            this.Sign = in.readString();
            this.s_tg_team = in.readString();
            this.s_m_place = in.readString();
            this.w_m_rate = in.readString();
            this.gold = in.readString();
            this.order_bet_amount = in.readDouble();
            this.havemoney = in.readString();
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
