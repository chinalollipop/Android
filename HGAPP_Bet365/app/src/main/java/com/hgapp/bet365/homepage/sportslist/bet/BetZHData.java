package com.hgapp.bet365.homepage.sportslist.bet;

import java.util.List;

public class BetZHData {

    private List<BetZHData.BetResultItemBean> betItem;

    public List<BetResultItemBean> getBetItem() {
        return betItem;
    }

    public void setBetItem(List<BetResultItemBean> betItem) {
        this.betItem = betItem;
    }

    public static class BetResultItemBean {
        public String s_league;
        public String s_mb_team;
        public String sign;
        public String s_tg_team;
        public String s_m_place;
        public String w_m_rate;

    }
}
