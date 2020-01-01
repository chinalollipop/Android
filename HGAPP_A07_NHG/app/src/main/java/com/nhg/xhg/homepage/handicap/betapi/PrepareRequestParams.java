package com.nhg.xhg.homepage.handicap.betapi;

import android.os.Parcel;
import android.os.Parcelable;

public class PrepareRequestParams implements Parcelable {
    public String cate;
    public String appRefer;
    /**
     * 选择玩法和赔率，准备投注接口
     * order/order_prepare_api.php
     *
     * @param  order_method FT_rm 滚球独赢，FT_re 滚球让球，FT_rou 滚球大小，FT_rt 滚球单双，FT_hrm 滚球半场独赢，FT_hre 滚球半场让球，FT_hrou 滚球半场大小，FT_m 独赢，FT_r 让球，FT_ou 大小，FT_t 单双，FT_hm 半场独赢，FT_hr 半场让球，FT_hou 半场大小，BK_re 滚球让球，BK_rou 滚球大小，BK_m 独赢，BK_r 让球，BK_ou 大小，BK_t 单双，BK_ouhc 球队得分大小
     * @param  gid
     * @param  type  H 主队 C 客队  N 和
     * @param  wtype  M 独赢，R 让球，大小 OU，单双 EO，半场独赢 HM，半场让球 HR，半场大小 HOU
     * @param  rtype  ODD 单 EVEN 双
     * @param  odd_f_type  H
     * @param  error_flag
     * @param  order_type
     */
    public String order_method;
    public String gid;
    public String type;
    public String wtype;
    public String rtype;
    public String odd_f_type;
    public String error_flag;
    public String order_type;
    public String autoOdd;

    public PrepareRequestParams(String cate, String appRefer, String order_method, String gid, String type, String wtype, String rtype, String odd_f_type, String error_flag, String order_type) {
        this.cate = cate;
        this.appRefer = appRefer;
        this.order_method = order_method;
        this.gid = gid;
        this.type = type;
        this.wtype = wtype;
        this.rtype = rtype;
        this.odd_f_type = odd_f_type;
        this.error_flag = error_flag;
        this.order_type = order_type;
    }

    public String getCate() {
        return cate;
    }

    public void setCate(String cate) {
        this.cate = cate;
    }

    public String getAppRefer() {
        return appRefer;//对应active
    }

    public void setAppRefer(String appRefer) {
        this.appRefer = appRefer;
    }

    public String getOrder_method() {
        return order_method;
    }

    public void setOrder_method(String order_method) {
        this.order_method = order_method;
    }

    public String getGid() {
        return gid;
    }

    public void setGid(String gid) {
        this.gid = gid;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getWtype() {
        return wtype;
    }

    public void setWtype(String wtype) {
        this.wtype = wtype;
    }

    public String getRtype() {
        return rtype;
    }

    public void setRtype(String rtype) {
        this.rtype = rtype;
    }

    public String getOdd_f_type() {
        return odd_f_type;
    }

    public void setOdd_f_type(String odd_f_type) {
        this.odd_f_type = odd_f_type;
    }

    public String getError_flag() {
        return error_flag;
    }

    public void setError_flag(String error_flag) {
        this.error_flag = error_flag;
    }

    public String getOrder_type() {
        return order_type;
    }

    public void setOrder_type(String order_type) {
        this.order_type = order_type;
    }

    public String getAutoOdd() {
        return autoOdd;
    }

    public void setAutoOdd(String autoOdd) {
        this.autoOdd = autoOdd;
    }

    @Override
    public String toString() {
        return "PrepareRequestParams{" +
                "cate='" + cate + '\'' +
                ", appRefer='" + appRefer + '\'' +
                ", order_method='" + order_method + '\'' +
                ", gid='" + gid + '\'' +
                ", type='" + type + '\'' +
                ", wtype='" + wtype + '\'' +
                ", rtype='" + rtype + '\'' +
                ", odd_f_type='" + odd_f_type + '\'' +
                ", error_flag='" + error_flag + '\'' +
                ", order_type='" + order_type + '\'' +
                ", autoOdd='" + autoOdd + '\'' +
                '}';
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.cate);
        dest.writeString(this.appRefer);
        dest.writeString(this.order_method);
        dest.writeString(this.gid);
        dest.writeString(this.type);
        dest.writeString(this.wtype);
        dest.writeString(this.rtype);
        dest.writeString(this.odd_f_type);
        dest.writeString(this.error_flag);
        dest.writeString(this.order_type);
        dest.writeString(this.autoOdd);
    }

    protected PrepareRequestParams(Parcel in) {
        this.cate = in.readString();
        this.appRefer = in.readString();
        this.order_method = in.readString();
        this.gid = in.readString();
        this.type = in.readString();
        this.wtype = in.readString();
        this.rtype = in.readString();
        this.odd_f_type = in.readString();
        this.error_flag = in.readString();
        this.order_type = in.readString();
        this.autoOdd = in.readString();
    }

    public static final Parcelable.Creator<PrepareRequestParams> CREATOR = new Parcelable.Creator<PrepareRequestParams>() {
        @Override
        public PrepareRequestParams createFromParcel(Parcel source) {
            return new PrepareRequestParams(source);
        }

        @Override
        public PrepareRequestParams[] newArray(int size) {
            return new PrepareRequestParams[size];
        }
    };
}
