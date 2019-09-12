package com.vene.tian.data;

import java.util.List;

public class BannerResult {


    /**
     * status : 200
     * describe : success
     * timestamp : 20180802034616
     * data : [{"img_path":"http://192.168.1.15/banner/focus1.png"},{"img_path":"http://192.168.1.15/banner/focus2.png"},{"img_path":"http://192.168.1.15/banner/focus3.png"}]
     * sign : 30c47a78d8bcbb800aba19c3ce6d3b47
     */

    private int status;
    private String describe;
    private String timestamp;
    private String sign;
    private List<DataBean> data;

    public int getStatus() {
        return status;
    }

    public void setStatus(int status) {
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
         * img_path : http://192.168.1.15/banner/focus1.png
         */
        private String name;
        private String img_path;
        public String getName() {
            return name;
        }

        public void setName(String name) {
            this.name = name;
        }
        public String getImg_path() {
            return img_path;
        }

        public void setImg_path(String img_path) {
            this.img_path = img_path;
        }
    }
}
