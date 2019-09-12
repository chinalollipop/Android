package com.sands.corp.homepage.handicap.leaguelist.championlist;

public class ChampionDetailSearchEvent {

    private String FStype;
    private String mtype;
    private String showtype;
    private String M_League;

    public ChampionDetailSearchEvent(String FStype, String mtype, String showtype, String m_League) {
        this.FStype = FStype;
        this.mtype = mtype;
        this.showtype = showtype;
        M_League = m_League;
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
}
