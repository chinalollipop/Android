package com.hfcp.hf.data;

import java.util.List;

public class RegisterLinkListResult {
    /**
     * totalUserCount : 0
     * aRegisterLinks : [{"id":168,"channel":"论坛","url":"http://dh5588.com/auth/signup?prize=6796b314795659f74749a8912cee6c7a","sExpireTimeFormatted":"永久有效","created_at":"2019-03-20 17:20:16","updated_at":"2019-03-20 17:20:16","username":"daniel02","valid_days":0,"status":"开启","is_agent":"代理"},{"id":167,"channel":"论坛","url":"http://dh5588.com/auth/signup?prize=088ebfa61b1b8b9e869b9b4cc69531e5","sExpireTimeFormatted":"永久有效","created_at":"2019-03-20 17:19:42","updated_at":"2019-03-20 17:19:42","username":"daniel02","valid_days":0,"status":"开启","is_agent":"代理"},{"id":166,"channel":"论坛","url":"http://dh5588.com/auth/signup?prize=f628c6ed25cd18d1e28d5311c022ad35","sExpireTimeFormatted":"永久有效","created_at":"2019-03-20 17:18:45","updated_at":"2019-03-20 17:19:11","username":"daniel02","valid_days":0,"status":"已关闭","is_agent":"代理"}]
     * iCount : 3
     * page : 1
     * pagesize : 20
     */

    private int totalUserCount;
    private int iCount;
    private int page;
    private int pagesize;
    private List<ARegisterLinksBean> aRegisterLinks;

    public int getTotalUserCount() {
        return totalUserCount;
    }

    public void setTotalUserCount(int totalUserCount) {
        this.totalUserCount = totalUserCount;
    }

    public int getICount() {
        return iCount;
    }

    public void setICount(int iCount) {
        this.iCount = iCount;
    }

    public int getPage() {
        return page;
    }

    public void setPage(int page) {
        this.page = page;
    }

    public int getPagesize() {
        return pagesize;
    }

    public void setPagesize(int pagesize) {
        this.pagesize = pagesize;
    }

    public List<ARegisterLinksBean> getARegisterLinks() {
        return aRegisterLinks;
    }

    public void setARegisterLinks(List<ARegisterLinksBean> aRegisterLinks) {
        this.aRegisterLinks = aRegisterLinks;
    }

    public static class ARegisterLinksBean {
        /**
         * id : 168
         * channel : 论坛
         * url : http://dh5588.com/auth/signup?prize=6796b314795659f74749a8912cee6c7a
         * sExpireTimeFormatted : 永久有效
         * created_at : 2019-03-20 17:20:16
         * updated_at : 2019-03-20 17:20:16
         * username : daniel02
         * valid_days : 0
         * status : 开启
         * is_agent : 代理
         */

        private int id;
        private String channel;
        private String url;
        private String sExpireTimeFormatted;
        private String created_at;
        private String updated_at;
        private String username;
        private int valid_days;
        private String status;
        private String is_agent;

        public int getId() {
            return id;
        }

        public void setId(int id) {
            this.id = id;
        }

        public String getChannel() {
            return channel;
        }

        public void setChannel(String channel) {
            this.channel = channel;
        }

        public String getUrl() {
            return url;
        }

        public void setUrl(String url) {
            this.url = url;
        }

        public String getSExpireTimeFormatted() {
            return sExpireTimeFormatted;
        }

        public void setSExpireTimeFormatted(String sExpireTimeFormatted) {
            this.sExpireTimeFormatted = sExpireTimeFormatted;
        }

        public String getCreated_at() {
            return created_at;
        }

        public void setCreated_at(String created_at) {
            this.created_at = created_at;
        }

        public String getUpdated_at() {
            return updated_at;
        }

        public void setUpdated_at(String updated_at) {
            this.updated_at = updated_at;
        }

        public String getUsername() {
            return username;
        }

        public void setUsername(String username) {
            this.username = username;
        }

        public int getValid_days() {
            return valid_days;
        }

        public void setValid_days(int valid_days) {
            this.valid_days = valid_days;
        }

        public String getStatus() {
            return status;
        }

        public void setStatus(String status) {
            this.status = status;
        }

        public String getIs_agent() {
            return is_agent;
        }

        public void setIs_agent(String is_agent) {
            this.is_agent = is_agent;
        }
    }
}
