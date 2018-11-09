package com.hgapp.a0086.data;

import java.util.List;

/**
 * Created by ak on 2017/9/25.
 */

public class DomainUrl {
    /**
     * "title": "",
     "list": [
     {
     "pid": "b79",
     "url": "https://m.ag13804.com"
     }
     ]
     */
    private String title;
    public String getTitle() {
        return title;
    }

    public void setTitle(String pid) {
        this.title = pid;
    }
    private List<ListBean> list;

    public List<ListBean> getList() {
        return list;
    }

    public void setList(List<ListBean> list) {
        this.list = list;
    }

    public static class ListBean {
        /**
         *  "pid": "b79",
            "url": "https://m.ag13804.com"
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
