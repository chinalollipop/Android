package com.gmcp.gm.data;

import java.util.List;

public class EmailBoxListResult {

    private List<ListBean> list;

    public List<ListBean> getList() {
        return list;
    }

    public void setList(List<ListBean> list) {
        this.list = list;
    }

    public static class ListBean {
        /**
         * id : 25728
         * receiver_id : 5372
         * receiver : seven01
         * sender_id : 79
         * sender : eden001
         * msg_id : 15962
         * type_id : 2
         * msg_title : 版重新额
         * is_keep : 1
         * is_to_all : 0
         * is_readed : 1
         * is_deleted : 0
         * readed_at : 2019-03-21 09:19:34
         * deleted_at :
         * created_at : 2019-03-20 10:31:18
         */

        private int id;
        private int receiver_id;
        private String receiver;
        private int sender_id;
        private String sender;
        private int msg_id;
        private int type_id;
        private String msg_title;
        private int is_keep;
        private int is_to_all;
        private int is_readed;
        private int is_deleted;
        private String readed_at;
        private String deleted_at;
        private String created_at;

        public int getId() {
            return id;
        }

        public void setId(int id) {
            this.id = id;
        }

        public int getReceiver_id() {
            return receiver_id;
        }

        public void setReceiver_id(int receiver_id) {
            this.receiver_id = receiver_id;
        }

        public String getReceiver() {
            return receiver;
        }

        public void setReceiver(String receiver) {
            this.receiver = receiver;
        }

        public int getSender_id() {
            return sender_id;
        }

        public void setSender_id(int sender_id) {
            this.sender_id = sender_id;
        }

        public String getSender() {
            return sender;
        }

        public void setSender(String sender) {
            this.sender = sender;
        }

        public int getMsg_id() {
            return msg_id;
        }

        public void setMsg_id(int msg_id) {
            this.msg_id = msg_id;
        }

        public int getType_id() {
            return type_id;
        }

        public void setType_id(int type_id) {
            this.type_id = type_id;
        }

        public String getMsg_title() {
            return msg_title;
        }

        public void setMsg_title(String msg_title) {
            this.msg_title = msg_title;
        }

        public int getIs_keep() {
            return is_keep;
        }

        public void setIs_keep(int is_keep) {
            this.is_keep = is_keep;
        }

        public int getIs_to_all() {
            return is_to_all;
        }

        public void setIs_to_all(int is_to_all) {
            this.is_to_all = is_to_all;
        }

        public int getIs_readed() {
            return is_readed;
        }

        public void setIs_readed(int is_readed) {
            this.is_readed = is_readed;
        }

        public int getIs_deleted() {
            return is_deleted;
        }

        public void setIs_deleted(int is_deleted) {
            this.is_deleted = is_deleted;
        }

        public String getReaded_at() {
            return readed_at;
        }

        public void setReaded_at(String readed_at) {
            this.readed_at = readed_at;
        }

        public String getDeleted_at() {
            return deleted_at;
        }

        public void setDeleted_at(String deleted_at) {
            this.deleted_at = deleted_at;
        }

        public String getCreated_at() {
            return created_at;
        }

        public void setCreated_at(String created_at) {
            this.created_at = created_at;
        }
    }
}
