package com.sunapp.bloc.data;

import android.os.Parcel;
import android.os.Parcelable;

public class CheckUpgradeResult implements Parcelable {

    /**
     * version : 1.0
     * file_size : 3.24MB
     * file_path : http://192.168.1.15/app-release.apk
     * description : 1.0版本
     * is_force : 1
     * service_meiqia : https://static.meiqia.com/dist/standalone.html?_=t&eid=61033
     * service_qq : 59901788
     * service_wechat : hg0088ph
     * newcomer_guide : http://192.168.1.15/template/help.html
     * business_agent : http://www.baidu.com
     * lottery_link : http://www.baidu.com
     * discount_activity : http://www.baidu.com
     */

    private String version;
    private String file_size;
    private String file_path;
    private String description;
    private String is_force;
    private String service_meiqia;
    private String service_qq;
    private String service_wechat;
    private String newcomer_guide;
    private String business_agent;
    private String lottery_link;
    private String discount_activity;
    private String guest_login_must_input_phone;
    private String tpl_name;
    private String signSwitch;
    private String agentLoginUrl;

    public String getVersion() {
        return version;
    }

    public void setVersion(String version) {
        this.version = version;
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

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getIs_force() {
        return is_force;
    }

    public void setIs_force(String is_force) {
        this.is_force = is_force;
    }

    public String getService_meiqia() {
        return service_meiqia;
    }

    public void setService_meiqia(String service_meiqia) {
        this.service_meiqia = service_meiqia;
    }

    public String getService_qq() {
        return service_qq;
    }

    public void setService_qq(String service_qq) {
        this.service_qq = service_qq;
    }

    public String getService_wechat() {
        return service_wechat;
    }

    public void setService_wechat(String service_wechat) {
        this.service_wechat = service_wechat;
    }

    public String getNewcomer_guide() {
        return newcomer_guide;
    }

    public void setNewcomer_guide(String newcomer_guide) {
        this.newcomer_guide = newcomer_guide;
    }

    public String getBusiness_agent() {
        return business_agent;
    }

    public void setBusiness_agent(String business_agent) {
        this.business_agent = business_agent;
    }

    public String getLottery_link() {
        return lottery_link;
    }

    public void setLottery_link(String lottery_link) {
        this.lottery_link = lottery_link;
    }

    public String getDiscount_activity() {
        return discount_activity;
    }

    public void setDiscount_activity(String discount_activity) {
        this.discount_activity = discount_activity;
    }

    public String getGuest_login_must_input_phone() {
        return guest_login_must_input_phone;
    }

    public void setGuest_login_must_input_phone(String guest_login_must_input_phone) {
        this.guest_login_must_input_phone = guest_login_must_input_phone;
    }

    public String getTpl_name() {
        return tpl_name;
    }

    public void setTpl_name(String tpl_name) {
        this.tpl_name = tpl_name;
    }

    public String getSignSwitch() {
        return signSwitch;
    }

    public void setSignSwitch(String signSwitch) {
        this.signSwitch = signSwitch;
    }

    public String getAgentLoginUrl() {
        return agentLoginUrl;
    }

    public void setAgentLoginUrl(String agentLoginUrl) {
        this.agentLoginUrl = agentLoginUrl;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.version);
        dest.writeString(this.file_size);
        dest.writeString(this.file_path);
        dest.writeString(this.description);
        dest.writeString(this.is_force);
        dest.writeString(this.service_meiqia);
        dest.writeString(this.service_qq);
        dest.writeString(this.service_wechat);
        dest.writeString(this.newcomer_guide);
        dest.writeString(this.business_agent);
        dest.writeString(this.lottery_link);
        dest.writeString(this.discount_activity);
        dest.writeString(this.guest_login_must_input_phone);
        dest.writeString(this.tpl_name);
        dest.writeString(this.signSwitch);
        dest.writeString(this.agentLoginUrl);
    }

    public CheckUpgradeResult() {
    }

    protected CheckUpgradeResult(Parcel in) {
        this.version = in.readString();
        this.file_size = in.readString();
        this.file_path = in.readString();
        this.description = in.readString();
        this.is_force = in.readString();
        this.service_meiqia = in.readString();
        this.service_qq = in.readString();
        this.service_wechat = in.readString();
        this.newcomer_guide = in.readString();
        this.business_agent = in.readString();
        this.lottery_link = in.readString();
        this.discount_activity = in.readString();
        this.guest_login_must_input_phone = in.readString();
        this.tpl_name = in.readString();
        this.signSwitch = in.readString();
        this.agentLoginUrl = in.readString();
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
