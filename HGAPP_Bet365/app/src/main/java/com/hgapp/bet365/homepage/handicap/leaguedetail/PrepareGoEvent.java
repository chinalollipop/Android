package com.hgapp.bet365.homepage.handicap.leaguedetail;

public class PrepareGoEvent {
    private String mLeague;
    private String mTeamH;
    private String mTeamC;
    private String gid;
    private String gtype;
    private String showtype;
    private String userMoney;
    private String fromType;
    private String fromString;

    public PrepareGoEvent(String mLeague, String mTeamH, String mTeamC, String gid, String gtype, String showtype, String userMoney, String fromType ,String fromString) {
        this.mLeague = mLeague;
        this.mTeamH = mTeamH;
        this.mTeamC = mTeamC;
        this.gid = gid;
        this.gtype = gtype;
        this.showtype = showtype;
        this.userMoney = userMoney;
        this.fromType = fromType;
        this.fromString = fromString;
    }

    public String getmLeague() {
        return mLeague;
    }

    public void setmLeague(String mLeague) {
        this.mLeague = mLeague;
    }

    public String getmTeamH() {
        return mTeamH;
    }

    public void setmTeamH(String mTeamH) {
        this.mTeamH = mTeamH;
    }

    public String getmTeamC() {
        return mTeamC;
    }

    public void setmTeamC(String mTeamC) {
        this.mTeamC = mTeamC;
    }

    public String getGid() {
        return gid;
    }

    public void setGid(String gid) {
        this.gid = gid;
    }

    public String getGtype() {
        return gtype;
    }

    public void setGtype(String gtype) {
        this.gtype = gtype;
    }

    public String getShowtype() {
        return showtype;
    }

    public void setShowtype(String showtype) {
        this.showtype = showtype;
    }

    public String getUserMoney() {
        return userMoney;
    }

    public void setUserMoney(String userMoney) {
        this.userMoney = userMoney;
    }

    public String getFromType() {
        return fromType;
    }

    public void setFromType(String fromType) {
        this.fromType = fromType;
    }

    public String getFromString() {
        return fromString;
    }

    public void setFromString(String fromString) {
        this.fromString = fromString;
    }
}
