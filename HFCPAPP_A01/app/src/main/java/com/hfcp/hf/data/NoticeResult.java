package com.hfcp.hf.data;


import java.util.List;

public class NoticeResult {

    /**
     * errno : 0
     * error :
     * data : [{"id":99,"title":"【重要通知】请认准 彩易博 官方平台","created_at":"2018-06-28 21:27:40","is_readed":0},{"id":98,"title":"【风控通知】平台游戏规则","created_at":"2018-06-28 21:24:16","is_readed":0},{"id":112,"title":"【升级通知】升级维护通知2019年01月10日03:00-08:00","created_at":"2019-01-10 02:25:18","is_readed":0},{"id":111,"title":"【风控通知】系统采用最新VPN轨迹监测","created_at":"2018-12-19 14:09:02","is_readed":0},{"id":106,"title":"【重要通知】彩易博，棋牌正式上线啦！","created_at":"2018-11-05 14:24:52","is_readed":0},{"id":105,"title":"【重要通知】APP版本升级，请重新下载更新！","created_at":"2018-09-13 19:41:45","is_readed":0},{"id":90,"title":"【充值问题】关于微信充值提示【超过当日在该商户的微信扫码充值限额】的解决办法","created_at":"2018-03-26 13:22:02","is_readed":0},{"id":89,"title":"【平台优惠】注册存款即送18元彩金","created_at":"2018-03-26 13:14:28","is_readed":0}]
     * sign : c24f04d79834f3105983ea45db7e0cc6
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
         * id : 99
         * title : 【重要通知】请认准 彩易博 官方平台
         * created_at : 2018-06-28 21:27:40
         * is_readed : 0
         */

        private int id;
        private String title;
        private String created_at;
        private int is_readed;

        public int getId() {
            return id;
        }

        public void setId(int id) {
            this.id = id;
        }

        public String getTitle() {
            return title;
        }

        public void setTitle(String title) {
            this.title = title;
        }

        public String getCreated_at() {
            return created_at;
        }

        public void setCreated_at(String created_at) {
            this.created_at = created_at;
        }

        public int getIs_readed() {
            return is_readed;
        }

        public void setIs_readed(int is_readed) {
            this.is_readed = is_readed;
        }
    }
}
