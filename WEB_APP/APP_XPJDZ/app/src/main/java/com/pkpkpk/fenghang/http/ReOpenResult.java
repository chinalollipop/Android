package com.pkpkpk.fenghang.http;

public class ReOpenResult {

    /**
     * title : 项目名称
     * url :URL地址
     * adopen:是否到期
     * contact:联系方式QQ：
     */

    private String title;
    private String url;
    private String adopen;
    private String contact;

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getUrl() {
        return url;
    }

    public void setUrl(String url) {
        this.url = url;
    }

    public String getAdopen() {
        return adopen;
    }

    public void setAdopen(String adopen) {
        this.adopen = adopen;
    }

    public String getContact() {
        return contact;
    }

    public void setContact(String contact) {
        this.contact = contact;
    }
}
