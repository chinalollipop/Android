package com.pkpkpk.newatoa.http.request;


public class TextMessage extends AbstractMessage {
    private static final long serialVersionUID = -4007300357161426922L;

    public TextMessage() {
    }

    public TextMessage(String status) {
        this.setStatus(status);
    }

    public TextMessage(String status, String describe) {
        this.setStatus(status);
        this.setDescribe(describe);
    }
}
