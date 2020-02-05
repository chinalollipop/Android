package com.hgapp.a6668.homepage.handicap.leaguedetail;

public class BKDataList {
    String gid;
    String team_h;
    String team_c;
    String ior_RH;
    String ior_RC;
    String ratio;
    String strong;

    public BKDataList(String gid, String team_h, String team_c, String ior_RH, String ior_RC, String ratio, String strong) {
        this.gid = gid;
        this.team_h = team_h;
        this.team_c = team_c;
        this.ior_RH = ior_RH;
        this.ior_RC = ior_RC;
        this.ratio = ratio;
        this.strong = strong;
    }

    public String getGid() {
        return gid;
    }

    public void setGid(String gid) {
        this.gid = gid;
    }

    public String getTeam_h() {
        return team_h;
    }

    public void setTeam_h(String team_h) {
        this.team_h = team_h;
    }

    public String getTeam_c() {
        return team_c;
    }

    public void setTeam_c(String team_c) {
        this.team_c = team_c;
    }

    public String getIor_RH() {
        return ior_RH;
    }

    public void setIor_RH(String ior_RH) {
        this.ior_RH = ior_RH;
    }

    public String getIor_RC() {
        return ior_RC;
    }

    public void setIor_RC(String ior_RC) {
        this.ior_RC = ior_RC;
    }

    public String getRatio() {
        return ratio;
    }

    public void setRatio(String ratio) {
        this.ratio = ratio;
    }

    public String getStrong() {
        return strong;
    }

    public void setStrong(String strong) {
        this.strong = strong;
    }
}
