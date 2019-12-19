package com.hgapp.a6668.data;

public class AGLiveResult {

    /**
     * name : 水果拉霸
     * gameurl : /images/aggame/FRU_ZH.png
     * gameid : 101
     */

    private String name;
    private String gameurl;
    private String gameid;
    private String item_id;

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getGameurl() {
        return gameurl;
    }

    public void setGameurl(String gameurl) {
        this.gameurl = gameurl;
    }

    public String getGameid() {
        return gameid;
    }

    public void setGameid(String gameid) {
        this.gameid = gameid;
    }

    public String getItem_id() {
        return item_id;
    }

    public void setItem_id(String item_id) {
        this.item_id = item_id;
    }

    @Override
    public String toString() {
        return "AGLiveResult{" +
                "name='" + name + '\'' +
                ", gameurl='" + gameurl + '\'' +
                ", gameid='" + gameid + '\'' +
                ", item_id='" + item_id + '\'' +
                '}';
    }
}
