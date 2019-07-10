package com.qpweb.a01.data;

import java.util.List;

public class TouziYestodayResult {


    /**
     * yestoday_list : [{"nickname":"Hhh","gold":"100.0000","pay_back_gold":"0.0000"}]
     * touzi_count_down : 10:16:38
     * today_touzi_gold : null
     * today_touzi_count : 0
     * today_my_touzi_gold : null
     */

    private String signin_count_down;//投资倒计时
    private String signin_people_number;//已签到人数
    private String yestoday_touzi_gold;//昨日投资额
    private String yestoday_touzi_count;//已投资人数
    private String yestoday_my_touzi_gold;//昨日已投资

    public String getSignin_count_down() {
        return signin_count_down;
    }

    public void setSignin_count_down(String signin_count_down) {
        this.signin_count_down = signin_count_down;
    }

    public String getSignin_people_number() {
        return signin_people_number;
    }

    public void setSignin_people_number(String signin_people_number) {
        this.signin_people_number = signin_people_number;
    }

    public String getYestoday_touzi_gold() {
        return yestoday_touzi_gold;
    }

    public void setYestoday_touzi_gold(String yestoday_touzi_gold) {
        this.yestoday_touzi_gold = yestoday_touzi_gold;
    }

    public String getYestoday_touzi_count() {
        return yestoday_touzi_count;
    }

    public void setYestoday_touzi_count(String yestoday_touzi_count) {
        this.yestoday_touzi_count = yestoday_touzi_count;
    }

    public String getYestoday_my_touzi_gold() {
        return yestoday_my_touzi_gold;
    }

    public void setYestoday_my_touzi_gold(String yestoday_my_touzi_gold) {
        this.yestoday_my_touzi_gold = yestoday_my_touzi_gold;
    }

    private String touzi_count_down;//投资倒计时
    private String today_touzi_gold;//今日投资额
    private String today_touzi_count;//今日投资人数
    private String today_my_touzi_gold;//今日已投资

    private List<YestodayListBean> yestoday_list;
    /**
     * current_time : 2019-06-24 14:24:58
     * day_part : 3
     */

    private String current_time;
    private int day_part;//【1 签到时间】 【2 投资金额正在分配】 【3 投资时间】

    public String getTouzi_count_down() {
        return touzi_count_down;
    }

    public void setTouzi_count_down(String touzi_count_down) {
        this.touzi_count_down = touzi_count_down;
    }

    public String getToday_touzi_gold() {
        return today_touzi_gold;
    }

    public void setToday_touzi_gold(String today_touzi_gold) {
        this.today_touzi_gold = today_touzi_gold;
    }

    public String getToday_touzi_count() {
        return today_touzi_count;
    }

    public void setToday_touzi_count(String today_touzi_count) {
        this.today_touzi_count = today_touzi_count;
    }

    public String getToday_my_touzi_gold() {
        return today_my_touzi_gold;
    }

    public void setToday_my_touzi_gold(String today_my_touzi_gold) {
        this.today_my_touzi_gold = today_my_touzi_gold;
    }

    public List<YestodayListBean> getYestoday_list() {
        return yestoday_list;
    }

    public void setYestoday_list(List<YestodayListBean> yestoday_list) {
        this.yestoday_list = yestoday_list;
    }

    public String getCurrent_time() {
        return current_time;
    }

    public void setCurrent_time(String current_time) {
        this.current_time = current_time;
    }

    public int getDay_part() {
        return day_part;
    }

    public void setDay_part(int day_part) {
        this.day_part = day_part;
    }

    public static class YestodayListBean {
        /**
         * nickname : Hhh
         * gold : 100.0000
         * pay_back_gold : 0.0000
         */

        private String nickname;
        private String gold;
        private String pay_back_gold;

        public String getNickname() {
            return nickname;
        }

        public void setNickname(String nickname) {
            this.nickname = nickname;
        }

        public String getGold() {
            return gold;
        }

        public void setGold(String gold) {
            this.gold = gold;
        }

        public String getPay_back_gold() {
            return pay_back_gold;
        }

        public void setPay_back_gold(String pay_back_gold) {
            this.pay_back_gold = pay_back_gold;
        }
    }
}
