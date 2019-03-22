package com.cfcp.a01.data;

import java.util.List;

public class BannerResult {

    /**
     * errno : 0
     * error :
     * data : [{"path":"http://dh5588.com/ad/2a923ccd904bf6c3acce202cdc25a539.png","url":"http://dh5588.com","notice_id":""},{"path":"http://dh5588.com/ad/efe44b36dbef52f55f5336d9cb4dd6d6.png","url":"http://dh5588.com","notice_id":""},{"path":"http://dh5588.com/ad/927071925d1ebda8acaebcb007a79c8d.png","url":"http://dh5588.com","notice_id":""},{"path":"http://dh5588.com/ad/e621990adb4b20afaeae5a1ea8f03329.png","url":"http://dh5588.com","notice_id":""},{"path":"http://dh5588.com/ad/fcb114d5c5aa000e2e1b2d97f9e5a741.jpeg","url":"http://dh5588.com","notice_id":""},{"path":"http://dh5588.com/ad/755056dc723e2174ec28b1ffbdc3fae9.jpeg","url":"http://dh5588.com","notice_id":""}]
     * sign : 6229de850a53cd15e0a6461c94812414
     */

    private int errno;
    private String error;
    private String sign;
    private List<DataBean> data;

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
         * path : http://dh5588.com/ad/2a923ccd904bf6c3acce202cdc25a539.png
         * url : http://dh5588.com
         * notice_id :
         */

        private String path;
        private String url;
        private String notice_id;

        public String getPath() {
            return path;
        }

        public void setPath(String path) {
            this.path = path;
        }

        public String getUrl() {
            return url;
        }

        public void setUrl(String url) {
            this.url = url;
        }

        public String getNotice_id() {
            return notice_id;
        }

        public void setNotice_id(String notice_id) {
            this.notice_id = notice_id;
        }
    }
}
