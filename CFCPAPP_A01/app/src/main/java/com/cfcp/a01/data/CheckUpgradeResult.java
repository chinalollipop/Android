package com.cfcp.a01.data;

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
    }

    public CheckUpgradeResult() {
    }

    protected CheckUpgradeResult(Parcel in) {
        this.version = in.readString();
        this.is_force = in.readInt();
        this.file_size = in.readString();
        this.file_path = in.readString();
        this.start_time = in.readString();
        this.description = in.readString();
    }

    public static final Parcelable.Creator<CheckUpgradeResult> CREATOR = new Parcelable.Creator<CheckUpgradeResult>() {
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