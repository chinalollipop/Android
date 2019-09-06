package com.gmcp.gm.data;

import java.io.Serializable;

public class BetDataResult implements Serializable {

    /**
     * errno : 0
     * error :
     * data : {"id":85}
     * sign : e87af64129f75fcbd57dfff0c9f10545
     */

    private int errno;
    private String error;
    private DataBean data;
    private String sign;

    public int getErrno() {
        return errno;
    }

    public void setErrno(int errno) {
        this.errno = errno;
    }

    public String getError() {
        return error;
    }

    public void setError(String error) {
        this.error = error;
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
         * id : 85
         */

        private int id;

        public int getId() {
            return id;
        }

        public void setId(int id) {
            this.id = id;
        }
    }
}
