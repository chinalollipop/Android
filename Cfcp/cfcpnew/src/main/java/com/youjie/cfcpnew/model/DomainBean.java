package com.youjie.cfcpnew.model;

import java.util.List;

public class DomainBean {

    /**
     * title : 移动url域名中心
     * list : [{"pid":"cfqp","url":"http://cfapi.app/main/cf55/"},{"pid":"cfqp","url":"http://cf508.com/main/cf55/"}]
     */

    private String title;
    private List<ListBean> list;

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public List<ListBean> getList() {
        return list;
    }

    public void setList(List<ListBean> list) {
        this.list = list;
    }

    public static class ListBean {
        /**
         * pid : cfqp
         * url : http://cfapi.app/main/cf55/
         */

        private String pid;
        private String url;

        public String getPid() {
            return pid;
        }

        public void setPid(String pid) {
            this.pid = pid;
        }

        public String getUrl() {
            return url;
        }

        public void setUrl(String url) {
            this.url = url;
        }
    }
}
