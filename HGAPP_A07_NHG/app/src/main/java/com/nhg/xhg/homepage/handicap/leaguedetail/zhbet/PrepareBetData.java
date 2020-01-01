package com.nhg.xhg.homepage.handicap.leaguedetail.zhbet;

public class PrepareBetData {
    public int isChecked;
    public int position;
    public int type;//类型
    public String strong;
    public String ratioHName;
    public String ratioCName;
    public String ratioH;
    public String ratioC;
    public String ratioN;
    public String ratioUp;
    public String ratioDown;
    public String ratioHMethod;
    public String ratioCMethod;
    public String ratioNMethod;

    @Override
    public String toString() {
        return "PrepareBetData{" +
                "isChecked=" + isChecked +
                ", position=" + position +
                ", type=" + type +
                ", strong='" + strong + '\'' +
                ", ratioHName='" + ratioHName + '\'' +
                ", ratioCName='" + ratioCName + '\'' +
                ", ratioH='" + ratioH + '\'' +
                ", ratioC='" + ratioC + '\'' +
                ", ratioN='" + ratioN + '\'' +
                ", ratioUp='" + ratioUp + '\'' +
                ", ratioDown='" + ratioDown + '\'' +
                ", ratioHMethod='" + ratioHMethod + '\'' +
                ", ratioCMethod='" + ratioCMethod + '\'' +
                ", ratioNMethod='" + ratioNMethod + '\'' +
                '}';
    }
}
