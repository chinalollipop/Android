package com.qpweb.a01.http;

/**
 * Created by Nereus on 2017/6/29.
 */

public class ClientConfig {


    public String productOwner;
    public String channelID;
    public String appRefer;
    public String version;
    public String locale;
    public String deviceId;

    public ClientConfig() {
    }

    public ClientConfig(String productOwner,String channelID, String appRefer, String version, String locale, String deviceId) {
        this.productOwner = productOwner;
        this.channelID = channelID;
        this.appRefer = appRefer;
        this.version = version;
        this.locale = locale;
        this.deviceId = deviceId;
    }

    @Override
    public String toString() {
        return "ClientConfig{" +
                "productOwner='" + productOwner + '\'' +
                ", channelID='" + channelID + '\'' +
                ", appRefer='" + appRefer + '\'' +
                ", version='" + version + '\'' +
                ", locale='" + locale + '\'' +
                ", deviceId='" + deviceId + '\'' +
                '}';
    }
}
