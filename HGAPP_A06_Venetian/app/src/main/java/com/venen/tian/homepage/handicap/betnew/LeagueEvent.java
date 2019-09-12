package com.venen.tian.homepage.handicap.betnew;

public class LeagueEvent {
    LeagueEvent(){}

    public LeagueEvent(String leagueNumber) {
        LeagueNumber = leagueNumber;
    }

    private String LeagueNumber;

    public String getLeagueNumber() {
        return LeagueNumber;
    }

    public void setLeagueNumber(String leagueNumber) {
        LeagueNumber = leagueNumber;
    }

}
