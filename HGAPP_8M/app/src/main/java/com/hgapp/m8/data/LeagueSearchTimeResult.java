package com.hgapp.m8.data;

import java.util.List;

public class LeagueSearchTimeResult {
    /**
     * status : 200
     * describe : success
     * timestamp : 20180819233910
     * data : [{"date":"2018-08-19","date_txt":"08月19日"},{"date":"2018-08-20","date_txt":"08月20日"},{"date":"2018-08-21","date_txt":"08月21日"},{"date":"2018-08-22","date_txt":"08月22日"},{"date":"2018-08-23","date_txt":"08月23日"},{"date":"2018-08-24","date_txt":"08月24日"},{"date":"2018-08-25","date_txt":"08月25日"},{"date":"2018-08-26","date_txt":"08月26日"},{"date":"2018-08-27","date_txt":"08月27日"},{"date":"2018-08-28","date_txt":"08月28日"},{"date":"2018-08-29","date_txt":"08月29日"},{"date":"2018-08-30","date_txt":"08月30日"},{"date":"2018-08-31","date_txt":"08月31日"},{"date":"2018-09-01","date_txt":"09月01日"},{"date":"2018-09-02","date_txt":"09月02日"}]
     * sign : 6805b71514cd6ea033693470e339b145
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

    public static class DataBean {
        /**
         * date : 2018-08-19
         * date_txt : 08月19日
         */

        private String date;
        private String date_txt;

        public String getDate() {
            return date;
        }

        public void setDate(String date) {
            this.date = date;
        }

        public String getDate_txt() {
            return date_txt;
        }

        public void setDate_txt(String date_txt) {
            this.date_txt = date_txt;
        }
    }

    @Override
    public String toString() {
        return "LeagueSearchTimeResult{" +
                "status='" + status + '\'' +
                ", describe='" + describe + '\'' +
                ", timestamp='" + timestamp + '\'' +
                ", sign='" + sign + '\'' +
                ", data=" + data +
                '}';
    }
}
