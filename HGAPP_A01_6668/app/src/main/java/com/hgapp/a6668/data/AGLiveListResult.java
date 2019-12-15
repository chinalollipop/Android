package com.hgapp.a6668.data;

import java.util.List;

public class AGLiveListResult {
    /**
     * status : 200
     * describe : success
     * timestamp : 20180710043412
     * data : [{"name":"百家乐","gameurl":"/images/live/girl1.png"},{"name":"龙虎斗","gameurl":"/images/live/girl2.png"},{"name":"轮盘","gameurl":"/images/live/girl3.png"},{"name":"骰子","gameurl":"/images/live/girl4.png"}]
     * sign : 9b9c6cdb01e1bd918b1af4f7f6c034f0
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
         * name : 百家乐
         * gameurl : /images/live/girl1.png
         */

        private String name;
        private String gameurl;

        public String getName() {
            return name;
        }

        public void setName(String name) {
            this.name = name;
        }

        public String getGameurl() {
            return gameurl;
        }

        public void setGameurl(String gameurl) {
            this.gameurl = gameurl;
        }
    }
}
