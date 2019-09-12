package com.vene.tian.data;

import android.os.Parcel;
import android.os.Parcelable;

import java.util.List;

public class BetZHResult {

    /**
     * status : 200
     * describe : 投注成功
     * timestamp : 20180920221542
     * data : [{"caption":"足球早餐综合过关交易单","Order_Bet_success":"交易成功单号：","order":"NOGK809208060510149102","s_league":"阿根廷超级联赛,阿根廷超级联赛,阿根廷超级联赛","btype":"","M_Date":"09-22","s_mb_team":"圣罗伦素,科隆竞技,纽维尔旧生","sign":"0.5 / 1,0,0 / 0.5","s_tg_team":"佩卓纳托,葛度尔古斯,拉鲁斯","s_m_place":"佩卓纳托,科隆竞技,纽维尔旧生","w_m_rate":"1.87,1.82,1.88","gold":"30","order_bet_amount":161.95,"havemoney":8854.205}]
     * sign :
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
         * caption : 足球早餐综合过关交易单
         * Order_Bet_success : 交易成功单号：
         * order : NOGK809208060510149102
         * s_league : 阿根廷超级联赛,阿根廷超级联赛,阿根廷超级联赛
         * btype :
         * M_Date : 09-22
         * s_mb_team : 圣罗伦素,科隆竞技,纽维尔旧生
         * sign : 0.5 / 1,0,0 / 0.5
         * s_tg_team : 佩卓纳托,葛度尔古斯,拉鲁斯
         * s_m_place : 佩卓纳托,科隆竞技,纽维尔旧生
         * w_m_rate : 1.87,1.82,1.88
         * gold : 30
         * order_bet_amount : 161.95
         * havemoney : 8854.205
         */

        private String caption;
        private String Order_Bet_success;
        private String order;
        private String s_league;
        private String btype;
        private String M_Date;
        private String s_mb_team;
        private String sign;
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

        public String getS_league() {
            return s_league;
        }

        public void setS_league(String s_league) {
            this.s_league = s_league;
        }

        public String getBtype() {
            return btype;
        }

        public void setBtype(String btype) {
            this.btype = btype;
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
            return sign;
        }

        public void setSign(String sign) {
            this.sign = sign;
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
            dest.writeString(this.s_league);
            dest.writeString(this.btype);
            dest.writeString(this.M_Date);
            dest.writeString(this.s_mb_team);
            dest.writeString(this.sign);
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
            this.s_league = in.readString();
            this.btype = in.readString();
            this.M_Date = in.readString();
            this.s_mb_team = in.readString();
            this.sign = in.readString();
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
