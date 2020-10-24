package com.hgapp.a0086.homepage.handicap.leaguedetail;

public class ComPassSearchEvent {
    public String getArgParam1;
    public String getArgParam2;
    public String getArgParam3;
    public String appRefer;
    public String gtype;
    public String sorttype;
    public String mdate;
    public String showtype;
    public String M_league;

    public ComPassSearchEvent(String getArgParam1, String getArgParam2, String getArgParam3, String appRefer, String gtype, String sorttype, String mdate, String showtype, String m_league) {
        this.getArgParam1 = getArgParam1;
        this.getArgParam2 = getArgParam2;
        this.getArgParam3 = getArgParam3;
        this.appRefer = appRefer;
        this.gtype = gtype;
        this.sorttype = sorttype;
        this.mdate = mdate;
        this.showtype = showtype;
        M_league = m_league;
    }

    @Override
    public String toString() {
        return "ComPassSearchEvent{" +
                "getArgParam1='" + getArgParam1 + '\'' +
                ", getArgParam2='" + getArgParam2 + '\'' +
                ", getArgParam3='" + getArgParam3 + '\'' +
                ", appRefer='" + appRefer + '\'' +
                ", gtype='" + gtype + '\'' +
                ", sorttype='" + sorttype + '\'' +
                ", mdate='" + mdate + '\'' +
                ", showtype='" + showtype + '\'' +
                ", M_league='" + M_league + '\'' +
                '}';
    }
}
