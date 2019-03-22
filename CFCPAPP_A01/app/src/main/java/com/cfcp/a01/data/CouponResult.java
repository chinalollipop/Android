package com.cfcp.a01.data;

import java.util.List;

public class CouponResult {


    /**
     * errno : 0
     * error : null
     * data : [{"content":"圣诞元旦 双节钜惠","pic_url":"/ad/cf66b275b6abe4c67e762e031087092b.png","is_closed":0,"redirect_url":"https://yb6.me/img/aLhk/k6nA0inAn.jpg"},{"content":"开元亏损救援金","pic_url":"/ad/1b653361cf99044f9ef7279f55d71405.png","is_closed":0,"redirect_url":"https://yb6.me/img/aLhk/kmhPfDAZI.png"},{"content":"神秘彩金","pic_url":"/ad/880cb6b124512bd4f8ebfac7f8607346.png","is_closed":0,"redirect_url":"https://s22.postimg.cc/fnzn5qu9t/image.png"},{"content":"银联钱包转账教程","pic_url":"/ad/ccb10764cd7e72032f57d3b6727b2137.png","is_closed":0,"redirect_url":"https://yb6.me/img/aLhk/Ov45zX37H.png"},{"content":"支付宝充值送0.5%已结束","pic_url":"/ad/4cc01695353b3f2cabf4a0503c5d109c.png","is_closed":0,"redirect_url":"https://yb6.me/img/aLhk/OvLn7Qlqb.png"},{"content":"微信充值送0.5%=已结束","pic_url":"/ad/44add7fbfc6f5b6a43715c63a2225304.png","is_closed":0,"redirect_url":"https://yb6.me/img/aLhk/OvLYm9io6.png"},{"content":"三大承诺，为您保驾护航","pic_url":"/ad/401f3fa686874307045acd747f9c7eb3.jpeg","is_closed":0,"redirect_url":"https://yb6.me/img/aLhk/kmAv7Uona.jpg"},{"content":"闯关奖金","pic_url":"/ad/ae03104ad7ef211e7fff572f6e820beb.png","is_closed":0,"redirect_url":"https://yb6.me/img/aLhk/kmhJDtfPf.png"},{"content":"返水0.2%优惠活动","pic_url":"/ad/4d0a1ae83d7423e87dc97daaf62b4f36.png","is_closed":0,"redirect_url":"https://s22.postimg.cc/r40lbc5jl/image.png"},{"content":"中秋  充值+打码=彩金","pic_url":"/ad/3ead45ace7554e9fe91fa619fbfe3666.png","is_closed":0,"redirect_url":"https://i.postimg.cc/1zmH6DLz/image.png"},{"content":"国庆七天","pic_url":"/ad/0a3e311383e0b07f786d26b3d17bb330.png","is_closed":0,"redirect_url":"https://i.postimg.cc/DZ5sjxsp/-1a.png"},{"content":"每日打码大闯关","pic_url":"/ad/7a5c896b337dba45bb16c090ed03fd00.png","is_closed":0,"redirect_url":"https://yb6.me/img/aLhk/kQJpXwUUu.png"}]
     * sign : 468b182c43033858f7fa9d2a6278efa1
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
         * content : 圣诞元旦 双节钜惠
         * pic_url : /ad/cf66b275b6abe4c67e762e031087092b.png
         * is_closed : 0
         * redirect_url : https://yb6.me/img/aLhk/k6nA0inAn.jpg
         */
        private boolean isShow;
        private String content;
        private String pic_url;
        private int is_closed;
        private String redirect_url;

        public boolean isShow() {
            return isShow;
        }

        public void setShow(boolean show) {
            isShow = show;
        }

        public String getContent() {
            return content;
        }

        public void setContent(String content) {
            this.content = content;
        }

        public String getPic_url() {
            return pic_url;
        }

        public void setPic_url(String pic_url) {
            this.pic_url = pic_url;
        }

        public int getIs_closed() {
            return is_closed;
        }

        public void setIs_closed(int is_closed) {
            this.is_closed = is_closed;
        }

        public String getRedirect_url() {
            return redirect_url;
        }

        public void setRedirect_url(String redirect_url) {
            this.redirect_url = redirect_url;
        }
    }
}
