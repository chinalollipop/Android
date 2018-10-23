package com.hgapp.a6668.data;

import android.os.Parcel;
import android.os.Parcelable;

import java.util.ArrayList;
import java.util.List;

public class NoticeResult implements Parcelable {


    /**
     * status : 200
     * describe : success
     * timestamp : 20180802034616
     * data : [{"notice":"☆重要通知☆：收款银行账户会不定期更换，请您在每次转账汇款充值前重新获取最新的收款账户，如存款值停用的收款账户，公司无法查收，恕不负责！谢谢您的支持！","created_time":"2018-08-01"},{"notice":"足球赛事:11月29日 阿根廷甲组联赛 (独立队 VS 纽维尔旧生) 因进球不予计算导致滚球比分错误, 所有投注在21:13:09至21:15:45的注单一律取消..1111","created_time":"2018-04-13"},{"notice":"足球赛事:03月16日 阿根廷甲组联赛 (独立队 VS 纽维尔旧生) 因进球不予计算导致滚球比分错误, 所有投注在21:13:09至21:15:45的注单一律取消..","created_time":"2018-03-16"},{"notice":"足球赛事:03月16日 阿根廷甲组联赛 (独立队 VS 纽维尔旧生) 因进球不予计算导致滚球比分错误, 所有投注在21:13:09至21:15:45的注单一律取消..","created_time":"2018-03-16"},{"notice":"足球赛事:03月16日 阿根廷甲组联赛 (独立队 VS 纽维尔旧生) 因进球不予计算导致滚球比分错误, 所有投注在21:13:09至21:15:45的注单一律取消..","created_time":"2018-03-16"},{"notice":"足球赛事:03月16日 阿根廷甲组联赛 (独立队 VS 纽维尔旧生) 因进球不予计算导致滚球比分错误, 所有投注在21:13:09至21:15:45的注单一律取消..","created_time":"2018-03-16"},{"notice":"足球赛事:03月16日 阿根廷甲组联赛 (独立队 VS 纽维尔旧生) 因进球不予计算导致滚球比分错误, 所有投注在21:13:09至21:15:45的注单一律取消..","created_time":"2018-03-16"},{"notice":"足球赛事:11月29日 阿根廷甲组联赛 (独立队 VS 纽维尔旧生) 因进球不予计算导致滚球比分错误, 所有投注在21:13:09至21:15:45的注单一律取消..","created_time":"2018-02-28"},{"notice":"足球赛事:测试2","created_time":"2018-02-27"},{"notice":"足球赛事:测试公告","created_time":"2018-02-28"}]
     * sign : 30c47a78d8bcbb800aba19c3ce6d3b47
     */

    private int status;
    private String describe;
    private String timestamp;
    private String sign;
    private List<DataBean> data;

    public int getStatus() {
        return status;
    }

    public void setStatus(int status) {
        this.status = status;
    }

    public String getDescribe() {
        return describe;
    }

    public void setDescribe(String describe) {
        this.describe = describe;
    }

    public String getTimestamp() {
        return timestamp;
    }

    public void setTimestamp(String timestamp) {
        this.timestamp = timestamp;
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
         * notice : ☆重要通知☆：收款银行账户会不定期更换，请您在每次转账汇款充值前重新获取最新的收款账户，如存款值停用的收款账户，公司无法查收，恕不负责！谢谢您的支持！
         * created_time : 2018-08-01
         */

        private String notice;
        private String created_time;

        public String getNotice() {
            return notice;
        }

        public void setNotice(String notice) {
            this.notice = notice;
        }

        public String getCreated_time() {
            return created_time;
        }

        public void setCreated_time(String created_time) {
            this.created_time = created_time;
        }
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeInt(this.status);
        dest.writeString(this.describe);
        dest.writeString(this.timestamp);
        dest.writeString(this.sign);
        dest.writeList(this.data);
    }

    public NoticeResult() {
    }

    protected NoticeResult(Parcel in) {
        this.status = in.readInt();
        this.describe = in.readString();
        this.timestamp = in.readString();
        this.sign = in.readString();
        this.data = new ArrayList<DataBean>();
        in.readList(this.data, DataBean.class.getClassLoader());
    }

    public static final Parcelable.Creator<NoticeResult> CREATOR = new Parcelable.Creator<NoticeResult>() {
        @Override
        public NoticeResult createFromParcel(Parcel source) {
            return new NoticeResult(source);
        }

        @Override
        public NoticeResult[] newArray(int size) {
            return new NoticeResult[size];
        }
    };
}
