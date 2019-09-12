package com.sands.corp.homepage.handicap.betnew;

public class LeagueSearchEvent {

    //1滚球 2今日 3早盘
    private String leagueSearchSub;
    private String leagueSearchName;
    // 1 2 3 4 5 6
    private String leagueSearchType;

    public LeagueSearchEvent(String leagueSearchSub, String leagueSearchName, String leagueSearchType) {
        this.leagueSearchSub = leagueSearchSub;
        this.leagueSearchName = leagueSearchName;
        this.leagueSearchType = leagueSearchType;
    }

    public LeagueSearchEvent(String leagueSearchSub, String leagueSearchName) {
        this.leagueSearchSub = leagueSearchSub;
        this.leagueSearchName = leagueSearchName;
    }

    public String getLeagueSearchSub() {
        return leagueSearchSub;
    }

    public void setLeagueSearchSub(String leagueSearchSub) {
        this.leagueSearchSub = leagueSearchSub;
    }

    public String getLeagueSearchName() {
        return leagueSearchName;
    }

    public void setLeagueSearchName(String leagueSearchName) {
        this.leagueSearchName = leagueSearchName;
    }

    public String getLeagueSearchType() {
        return leagueSearchType;
    }

    public void setLeagueSearchType(String leagueSearchType) {
        this.leagueSearchType = leagueSearchType;
    }
}
