package com.nhg.xhg.homepage.handicap.leaguedetail;

public class LeagueDetailSearchEvent {
   /* @param  type   FT 足球，FU 足球早盘，BK 篮球，BU 篮球早盘
     * @param  more   s 今日赛事， r 滚球
     * @param  gid  3321118,3321062
    */
    private String type;
    private String more;
    private String gid;
    //滚球 今日 早盘
    private String showtype;

    public LeagueDetailSearchEvent(String type, String more, String gid, String showtype) {
        this.type = type;
        this.more = more;
        this.gid = gid;
        this.showtype = showtype;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getMore() {
        return more;
    }

    public void setMore(String more) {
        this.more = more;
    }

    public String getGid() {
        return gid;
    }

    public void setGid(String gid) {
        this.gid = gid;
    }

    public String getShowtype() {
        return showtype;
    }

    public void setShowtype(String showtype) {
        this.showtype = showtype;
    }

    @Override
    public String toString() {
        return "LeagueDetailSearchEvent{" +
                "type='" + type + '\'' +
                ", more='" + more + '\'' +
                ", gid='" + gid + '\'' +
                ", showtype='" + showtype + '\'' +
                '}';
    }
}
