package com.hfcp.hf.data;

import android.os.Parcel;
import android.os.Parcelable;

import java.util.List;

public class CPNoteResult implements Parcelable {
    /**
     * totalCount : 1
     * data : [{"title":"1","comment":"欢迎来到皇冠现金网","jsalert":"1","addtime":"2018-11-17 14:53:06"}]
     */

    private int totalCount;
    private List<DataBean> data;

    public int getTotalCount() {
        return totalCount;
    }

    public void setTotalCount(int totalCount) {
        this.totalCount = totalCount;
    }

    public List<DataBean> getData() {
        return data;
    }

    public void setData(List<DataBean> data) {
        this.data = data;
    }

    public static class DataBean implements Parcelable {
        /**
         * title : 1
         * comment : 欢迎来到皇冠现金网
         * jsalert : 1
         * addtime : 2018-11-17 14:53:06
         */

        private String title;
        private String comment;
        private String jsalert;
        private String addtime;

        public String getTitle() {
            return title;
        }

        public void setTitle(String title) {
            this.title = title;
        }

        public String getComment() {
            return comment;
        }

        public void setComment(String comment) {
            this.comment = comment;
        }

        public String getJsalert() {
            return jsalert;
        }

        public void setJsalert(String jsalert) {
            this.jsalert = jsalert;
        }

        public String getAddtime() {
            return addtime;
        }

        public void setAddtime(String addtime) {
            this.addtime = addtime;
        }

        @Override
        public int describeContents() {
            return 0;
        }

        @Override
        public void writeToParcel(Parcel dest, int flags) {
            dest.writeString(this.title);
            dest.writeString(this.comment);
            dest.writeString(this.jsalert);
            dest.writeString(this.addtime);
        }

        public DataBean() {
        }

        protected DataBean(Parcel in) {
            this.title = in.readString();
            this.comment = in.readString();
            this.jsalert = in.readString();
            this.addtime = in.readString();
        }

        public static final Creator<DataBean> CREATOR = new Creator<DataBean>() {
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
        dest.writeInt(this.totalCount);
        dest.writeTypedList(this.data);
    }

    public CPNoteResult() {
    }

    protected CPNoteResult(Parcel in) {
        this.totalCount = in.readInt();
        this.data = in.createTypedArrayList(DataBean.CREATOR);
    }

    public static final Creator<CPNoteResult> CREATOR = new Creator<CPNoteResult>() {
        @Override
        public CPNoteResult createFromParcel(Parcel source) {
            return new CPNoteResult(source);
        }

        @Override
        public CPNoteResult[] newArray(int size) {
            return new CPNoteResult[size];
        }
    };
}
