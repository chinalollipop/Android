package com.venen.tian.data;

public class MaintainResult {

    /**
     * type : sport
     * title : 体育维护
     * state : 1
     * content : 很抱歉，体育临时维护中。
     */

    private String type;
    private String title;
    private String state;
    private String content;

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getState() {
        return state;
    }

    public void setState(String state) {
        this.state = state;
    }

    public String getContent() {
        return content;
    }

    public void setContent(String content) {
        this.content = content;
    }
}
