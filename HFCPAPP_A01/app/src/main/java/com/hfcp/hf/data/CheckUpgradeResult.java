package com.hfcp.hf.data;

import android.os.Parcel;
import android.os.Parcelable;

public class CheckUpgradeResult implements Parcelable {

    /**
     * version : 1.0
     * is_force : 1
     * file_size : 12
     * file_path : http://baidu.com
     * start_time : 2019-03-15 13:39:00
     * description : 第一版
     */

    private String version;
    private int is_force;
    private String file_size;
    private String file_path;
    private String start_time;
    private String description;
    private String custom_service;
    private String is_reg_qq="0";//0不显示 1 显示
    private String isOpenGuest;//0不显示 1 显示
    private String ky_trygame_url;
    private String ly_trygame_url;


    public String getVersion() {
        return version;
    }

    public void setVersion(String version) {
        this.version = version;
    }

    public int getIs_force() {
        return is_force;
    }

    public void setIs_force(int is_force) {
        this.is_force = is_force;
    }

    public String getFile_size() {
        return file_size;
    }

    public void setFile_size(String file_size) {
        this.file_size = file_size;
    }

    public String getFile_path() {
        return file_path;
    }

    public void setFile_path(String file_path) {
        this.file_path = file_path;
    }

    public String getStart_time() {
        return start_time;
    }

    public void setStart_time(String start_time) {
        this.start_time = start_time;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getCustom_service() {
        return custom_service;
    }

    public void setCustom_service(String custom_service) {
        this.custom_service = custom_service;
    }

    public String getIs_reg_qq() {
        return is_reg_qq;
    }

    public void setIs_reg_qq(String is_reg_qq) {
        this.is_reg_qq = is_reg_qq;
    }

    public String getIsOpenGuest() {
        return isOpenGuest;
    }

    public void setIsOpenGuest(String isOpenGuest) {
        this.isOpenGuest = isOpenGuest;
    }

    public String getKy_trygame_url() {
        return ky_trygame_url;
    }

    public void setKy_trygame_url(String ky_trygame_url) {
        this.ky_trygame_url = ky_trygame_url;
    }

    public String getLy_trygame_url() {
        return ly_trygame_url;
    }

    public void setLy_trygame_url(String ly_trygame_url) {
        this.ly_trygame_url = ly_trygame_url;
    }

    public CheckUpgradeResult() {
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.version);
        dest.writeInt(this.is_force);
        dest.writeString(this.file_size);
        dest.writeString(this.file_path);
        dest.writeString(this.start_time);
        dest.writeString(this.description);
        dest.writeString(this.custom_service);
        dest.writeString(this.is_reg_qq);
        dest.writeString(this.isOpenGuest);
        dest.writeString(this.ky_trygame_url);
        dest.writeString(this.ly_trygame_url);
    }

    protected CheckUpgradeResult(Parcel in) {
        this.version = in.readString();
        this.is_force = in.readInt();
        this.file_size = in.readString();
        this.file_path = in.readString();
        this.start_time = in.readString();
        this.description = in.readString();
        this.custom_service = in.readString();
        this.is_reg_qq = in.readString();
        this.isOpenGuest = in.readString();
        this.ky_trygame_url = in.readString();
        this.ly_trygame_url = in.readString();
    }

    public static final Creator<CheckUpgradeResult> CREATOR = new Creator<CheckUpgradeResult>() {
        @Override
        public CheckUpgradeResult createFromParcel(Parcel source) {
            return new CheckUpgradeResult(source);
        }

        @Override
        public CheckUpgradeResult[] newArray(int size) {
            return new CheckUpgradeResult[size];
        }
    };
}
