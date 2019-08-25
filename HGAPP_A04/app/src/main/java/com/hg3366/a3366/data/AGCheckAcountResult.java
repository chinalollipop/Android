package com.hg3366.a3366.data;

public class AGCheckAcountResult {

    /**
     * status : 200
     * describe : AG帐号创建成功
     * timestamp : 20180923043756
     * data : true
     * sign :
     */

    private String status;
    private String describe;
    private String timestamp;
    private boolean data;
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

    public boolean isData() {
        return data;
    }

    public void setData(boolean data) {
        this.data = data;
    }

    public String getSign() {
        return sign;
    }

    public void setSign(String sign) {
        this.sign = sign;
    }

    @Override
    public String toString() {
        return "AGCheckAcountResult{" +
                "status='" + status + '\'' +
                ", describe='" + describe + '\'' +
                ", timestamp='" + timestamp + '\'' +
                ", data=" + data +
                ", sign='" + sign + '\'' +
                '}';
    }
}
