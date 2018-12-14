package com.hgapp.a6668.data;

import java.util.List;

public class CPQuickBetResult {

    private List<DataBean> data;

    public List<DataBean> getData() {
        return data;
    }

    public void setData(List<DataBean> data) {
        this.data = data;
    }

    public static class DataBean {
        /**
         * id : 21
         * userid : 3994
         * sort : 1
         * game_code : 2
         * code_number : 0
         * code : 1015
         * create_time : 2018-11-27 11:00:08
         * update_time : 2018-11-27 11:07:43
         */

        private String id;
        private String userid;
        private String sort;
        private String game_code;
        private String code_number;
        private String code;
        private String create_time;
        private String update_time;

        public String getId() {
            return id;
        }

        public void setId(String id) {
            this.id = id;
        }

        public String getUserid() {
            return userid;
        }

        public void setUserid(String userid) {
            this.userid = userid;
        }

        public String getSort() {
            return sort;
        }

        public void setSort(String sort) {
            this.sort = sort;
        }

        public String getGame_code() {
            return game_code;
        }

        public void setGame_code(String game_code) {
            this.game_code = game_code;
        }

        public String getCode_number() {
            return code_number;
        }

        public void setCode_number(String code_number) {
            this.code_number = code_number;
        }

        public String getCode() {
            return code;
        }

        public void setCode(String code) {
            this.code = code;
        }

        public String getCreate_time() {
            return create_time;
        }

        public void setCreate_time(String create_time) {
            this.create_time = create_time;
        }

        public String getUpdate_time() {
            return update_time;
        }

        public void setUpdate_time(String update_time) {
            this.update_time = update_time;
        }
    }
}
