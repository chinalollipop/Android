package com.flush.a01.data;

public class DomainAddResult {

    /**
     * status : 200
     * message : 刷水账号添加成功
     * data : {"id":true,"typeEx":null,"urlEx":null,"nameEx":"laobb020","passwdEx":"qaz123","uidEx":"61sk9abhm21757787l187808","datetime":"19-08-02 05:29:02","source":13,"cookie":"gamePoint_21757787=2019-08-02*0*0;hide_notice=;protocolstr=https;OddType=;_ga=GA1.2.239557531.1564639244;_gid=;_gat_UA=","status":0}
     */

    private int status;
    private String message;
    private DataBean data;

    public int getStatus() {
        return status;
    }

    public void setStatus(int status) {
        this.status = status;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public DataBean getData() {
        return data;
    }

    public void setData(DataBean data) {
        this.data = data;
    }

    public static class DataBean {
        /**
         * id : true
         * typeEx : null
         * urlEx : null
         * nameEx : laobb020
         * passwdEx : qaz123
         * uidEx : 61sk9abhm21757787l187808
         * datetime : 19-08-02 05:29:02
         * source : 13
         * cookie : gamePoint_21757787=2019-08-02*0*0;hide_notice=;protocolstr=https;OddType=;_ga=GA1.2.239557531.1564639244;_gid=;_gat_UA=
         * status : 0
         */

        private boolean id;
        private Object typeEx;
        private Object urlEx;
        private String nameEx;
        private String passwdEx;
        private String uidEx;
        private String datetime;
        private int source;
        private String cookie;
        private int status;

        public boolean isId() {
            return id;
        }

        public void setId(boolean id) {
            this.id = id;
        }

        public Object getTypeEx() {
            return typeEx;
        }

        public void setTypeEx(Object typeEx) {
            this.typeEx = typeEx;
        }

        public Object getUrlEx() {
            return urlEx;
        }

        public void setUrlEx(Object urlEx) {
            this.urlEx = urlEx;
        }

        public String getNameEx() {
            return nameEx;
        }

        public void setNameEx(String nameEx) {
            this.nameEx = nameEx;
        }

        public String getPasswdEx() {
            return passwdEx;
        }

        public void setPasswdEx(String passwdEx) {
            this.passwdEx = passwdEx;
        }

        public String getUidEx() {
            return uidEx;
        }

        public void setUidEx(String uidEx) {
            this.uidEx = uidEx;
        }

        public String getDatetime() {
            return datetime;
        }

        public void setDatetime(String datetime) {
            this.datetime = datetime;
        }

        public int getSource() {
            return source;
        }

        public void setSource(int source) {
            this.source = source;
        }

        public String getCookie() {
            return cookie;
        }

        public void setCookie(String cookie) {
            this.cookie = cookie;
        }

        public int getStatus() {
            return status;
        }

        public void setStatus(int status) {
            this.status = status;
        }
    }
}
