package com.qpweb.a01.data;

public class DepositAliPayQCCodeResult {

    /**
     * status : 200
     * describe : success
     * timestamp : 20190619144739
     * data : {"id":"42","bank_user":"支付宝4【Z10杨启】","photo_name":"http://i2.bvimg.com/671785/87a711b977746905.png","notice":"交易单号后四位"}
     * sign :
     */

    private String status;
    private String describe;
    private String timestamp;
    private DataBean data;
    private String sign;

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

    public DataBean getData() {
        return data;
    }

    public void setData(DataBean data) {
        this.data = data;
    }

    public String getSign() {
        return sign;
    }

    public void setSign(String sign) {
        this.sign = sign;
    }

    public static class DataBean {
        /**
         * id : 42
         * bank_user : 支付宝4【Z10杨启】
         * photo_name : http://i2.bvimg.com/671785/87a711b977746905.png
         * notice : 交易单号后四位
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
    }
}
