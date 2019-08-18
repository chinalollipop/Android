package com.flush.a01.data;

import java.util.List;

public class DomainAllResult {
    /**
     * status : 200
     * message : 刷水账号列表
     * data : [{"ID":"3","Datasite":"","Type":"zh-cn","Uid":"61sk9abhm21757787l187808","Name":"laobb020","Passwd":"qaz123","LiveID":null,"status":"0","datetime":"2019-08-02 01:58:36","source":"13","cookie":"gamePoint_21757787=2019-08-02*0*0;hide_notice=;protocolstr=https;OddType=;_ga=GA1.2.239557531.1564639244;_gid=;_gat_UA="}]
     */

    private int status;
    private String message;
    private List<DataBean> data;

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

    public List<DataBean> getData() {
        return data;
    }

    public void setData(List<DataBean> data) {
        this.data = data;
    }

    public static class DataBean {
        /**
         * ID : 3
         * Datasite :
         * Type : zh-cn
         * Uid : 61sk9abhm21757787l187808
         * Name : laobb020
         * Passwd : qaz123
         * LiveID : null
         * status : 0
         * datetime : 2019-08-02 01:58:36
         * source : 13
         * cookie : gamePoint_21757787=2019-08-02*0*0;hide_notice=;protocolstr=https;OddType=;_ga=GA1.2.239557531.1564639244;_gid=;_gat_UA=
         */

        private String ID;
        private String Datasite;
        private String Type;
        private String Uid;
        private String Name;
        private String Passwd;
        private Object LiveID;
        private String status;
        private String datetime;
        private String source;
        private String cookie;

        public String getID() {
            return ID;
        }

        public void setID(String ID) {
            this.ID = ID;
        }

        public String getDatasite() {
            return Datasite;
        }

        public void setDatasite(String Datasite) {
            this.Datasite = Datasite;
        }

        public String getType() {
            return Type;
        }

        public void setType(String Type) {
            this.Type = Type;
        }

        public String getUid() {
            return Uid;
        }

        public void setUid(String Uid) {
            this.Uid = Uid;
        }

        public String getName() {
            return Name;
        }

        public void setName(String Name) {
            this.Name = Name;
        }

        public String getPasswd() {
            return Passwd;
        }

        public void setPasswd(String Passwd) {
            this.Passwd = Passwd;
        }

        public Object getLiveID() {
            return LiveID;
        }

        public void setLiveID(Object LiveID) {
            this.LiveID = LiveID;
        }

        public String getStatus() {
            return status;
        }

        public void setStatus(String status) {
            this.status = status;
        }

        public String getDatetime() {
            return datetime;
        }

        public void setDatetime(String datetime) {
            this.datetime = datetime;
        }

        public String getSource() {
            return source;
        }

        public void setSource(String source) {
            this.source = source;
        }

        public String getCookie() {
            return cookie;
        }

        public void setCookie(String cookie) {
            this.cookie = cookie;
        }
    }
}
