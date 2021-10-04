package com.hgapp.a0086.homepage.handicap.leaguelist.championlist;

public class ChampionDetailSearchEvent {

    private String FStype;
    private String mtype;
    private String showtype;
    private String M_League;
    private String lid;

    public ChampionDetailSearchEvent(String FStype, String mtype, String showtype, String m_League, String lid) {
        this.FStype = FStype;
        this.mtype = mtype;
        this.showtype = showtype;
        this.M_League = m_League;
        this.lid = lid;
    }

    public String getFStype() {
        return FStype;
    }

    public void setFStype(String FStype) {
        this.FStype = FStype;
    }

    public String getMtype() {
        return mtype;
    }

    public void setMtype(String mtype) {
        this.mtype = mtype;
    }

    public String getShowtype() {
        return showtype;
    }

    public void setShowtype(String showtype) {
        this.showtype = showtype;
    }

    public String getM_League() {
        return M_League;
    }

    public void setM_League(String m_League) {
        M_League = m_League;
    }

    public String getLid() {
        return lid;
    }

    public void setLid(String lid) {
        this.lid = lid;
    }
}
