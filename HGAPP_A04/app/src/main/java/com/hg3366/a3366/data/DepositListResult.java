package com.hg3366.a3366.data;


import java.util.List;

public class DepositListResult {


    /**
     * status : 200
     * describe : success
     * timestamp : 20180912045256
     * data : [{"id":0,"bankid":"","title":"快速充值","api":"http://pay0086.com"},{"id":2,"bankid":"","title":"公司入款","api":"/account/deposit_two_bank_company_save.php"},{"id":6,"bankid":"19","title":"支付宝扫码","api":"/account/bank_type_ALISAOMA.php"},{"id":6,"bankid":"27","title":"支付宝扫码","api":"/account/bank_type_ALISAOMA.php"},{"id":7,"bankid":"17","title":"微信扫码","api":"/account/bank_type_WESAOMA.php"},{"id":7,"bankid":"26","title":"微信扫码","api":"/account/bank_type_WESAOMA.php"},{"id":7,"bankid":"29","title":"微信扫码","api":"/account/bank_type_WESAOMA.php"}]
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

    public static class DataBean {
        /**
         * id : 0
         * bankid :
         * title : 快速充值
         * api : http://pay0086.com
         */

        private int id;
        private String bankid;
        private String title;
        private String api;

        public int getId() {
            return id;
        }

        public void setId(int id) {
            this.id = id;
        }

        public String getBankid() {
            return bankid;
        }

        public void setBankid(String bankid) {
            this.bankid = bankid;
        }

        public String getTitle() {
            return title;
        }

        public void setTitle(String title) {
            this.title = title;
        }

        public String getApi() {
            return api;
        }

        public void setApi(String api) {
            this.api = api;
        }
    }
}
