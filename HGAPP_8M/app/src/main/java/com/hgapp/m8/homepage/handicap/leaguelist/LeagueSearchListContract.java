package com.hgapp.m8.homepage.handicap.leaguelist;

import com.hgapp.m8.base.IMessageView;
import com.hgapp.m8.base.IPresenter;
import com.hgapp.m8.base.IProgressView;
import com.hgapp.m8.base.IView;
import com.hgapp.m8.data.LeagueDetailSearchListResult;
import com.hgapp.m8.data.LeagueSearchListResult;
import com.hgapp.m8.data.LeagueSearchTimeResult;
import com.hgapp.m8.data.MaintainResult;

import java.util.List;

public interface LeagueSearchListContract {

    public interface Presenter extends IPresenter
    {
        public void postLeagueSearchTime(String appRefer);
        public void postLeagueSearchList(String appRefer, String gtype,String showtype, String sorttype,String date);
        public void postLeaguePassSearchList(String appRefer, String gtype,String showtype, String sorttype,String date);
        public void postLeagueSearchChampionList(String appRefer, String showtype,String FStype, String mtype);
        public void postLeagueDetailSearchList(String appRefer, String type,String more, String gid);
        public void postMaintain();
    }
    public interface View extends IView<LeagueSearchListContract.Presenter>,IMessageView,IProgressView
    {
        public void postLeagueSearchTimeResult(LeagueSearchTimeResult leagueSearchTimeResult);
        public void postLeagueSearchListResult(LeagueSearchListResult leagueSearchListResult);
        public void postLeagueSearchListNoDataResult(String message);
        public void postLeagueDetailSearchListResult(LeagueDetailSearchListResult leagueDetailSearchListResult);
        public void postMaintainResult(List<MaintainResult> maintainResult);
    }

}
