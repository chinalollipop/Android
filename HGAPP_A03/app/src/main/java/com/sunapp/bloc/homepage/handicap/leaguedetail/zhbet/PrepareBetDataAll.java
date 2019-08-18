package com.sunapp.bloc.homepage.handicap.leaguedetail.zhbet;

import java.util.ArrayList;

public class PrepareBetDataAll {
    public String name;
    public String atype;
    public ArrayList<PrepareBetData> prepareBetData;

    @Override
    public String toString() {
        return "PrepareBetDataAll{" +
                "name='" + name + '\'' +
                ", atype='" + atype + '\'' +
                ", prepareBetData=" + prepareBetData +
                '}';
    }
}
