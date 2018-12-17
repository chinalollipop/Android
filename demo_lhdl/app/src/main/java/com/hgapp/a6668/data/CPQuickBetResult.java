package com.hgapp.a6668.data;

import android.os.Parcel;
import android.os.Parcelable;

import java.util.List;

public class CPQuickBetResult implements Parcelable {

    private List<DataBean> data;

    public List<DataBean> getData() {
        return data;
    }

    public void setData(List<DataBean> data) {
        this.data = data;
    }

    public static class DataBean implements Parcelable {
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

        @Override
        public int describeContents() {
            return 0;
        }

        @Override
        public void writeToParcel(Parcel dest, int flags) {
            dest.writeString(this.id);
            dest.writeString(this.userid);
            dest.writeString(this.sort);
            dest.writeString(this.game_code);
            dest.writeString(this.code_number);
            dest.writeString(this.code);
            dest.writeString(this.create_time);
            dest.writeString(this.update_time);
        }

        public DataBean() {
        }

        protected DataBean(Parcel in) {
            this.id = in.readString();
            this.userid = in.readString();
            this.sort = in.readString();
            this.game_code = in.readString();
            this.code_number = in.readString();
            this.code = in.readString();
            this.create_time = in.readString();
            this.update_time = in.readString();
        }

        public static final Parcelable.Creator<DataBean> CREATOR = new Parcelable.Creator<DataBean>() {
            @Override
            public DataBean createFromParcel(Parcel source) {
                return new DataBean(source);
            }

            @Override
            public DataBean[] newArray(int size) {
                return new DataBean[size];
            }
        };
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeTypedList(this.data);
    }

    public CPQuickBetResult() {
    }

    protected CPQuickBetResult(Parcel in) {
        this.data = in.createTypedArrayList(DataBean.CREATOR);
    }

    public static final Parcelable.Creator<CPQuickBetResult> CREATOR = new Parcelable.Creator<CPQuickBetResult>() {
        @Override
        public CPQuickBetResult createFromParcel(Parcel source) {
            return new CPQuickBetResult(source);
        }

        @Override
        public CPQuickBetResult[] newArray(int size) {
            return new CPQuickBetResult[size];
        }
    };
}
