package com.hg3366.a3366.data;

import java.util.List;

public class SportsListResult {
    /**
     * status : 200
     * describe : success
     * timestamp : 20180820052737
     * data : [{"gid":"2589764,2589771,2589778,2589769,2589770","M_League":"亚运会2018男子篮球(在印尼)","num":5}]
     * sign : 8f9901f6bc74d4eb859be72ffbdcbcb5
     */

    private String status;
    private String describe;
    private String timestamp;
    private String sign;
    private List<LeagueSearchListResult.DataBean> data;

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

    public List<LeagueSearchListResult.DataBean> getData() {
        return data;
    }

    public void setData(List<LeagueSearchListResult.DataBean> data) {
        this.data = data;
    }

    public static class DataBean {
        /**
         * gid : 2589764,2589771,2589778,2589769,2589770
         * M_League : 亚运会2018男子篮球(在印尼)
         * num : 5
         */

        private String gid;
        private String M_League;
        private int num;

        public String getGid() {
            return gid;
        }

        public void setGid(String gid) {
            this.gid = gid;
        }

        public String getM_League() {
            return M_League;
        }

        public void setM_League(String M_League) {
            this.M_League = M_League;
        }

        public int getNum() {
            return num;
        }

        public void setNum(int num) {
            this.num = num;
        }
    }
}
