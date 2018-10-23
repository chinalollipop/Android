package com.hgapp.a0086.homepage.handicap.leaguelist;

import com.hgapp.a0086.base.IMessageView;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.base.IProgressView;
import com.hgapp.a0086.base.IView;
import com.hgapp.a0086.data.LeagueDetailSearchListResult;
import com.hgapp.a0086.data.LeagueSearchListResult;
import com.hgapp.a0086.data.LeagueSearchTimeResult;

public interface LeagueSearchListContract {

    public interface Presenter extends IPresenter
    {
        public void postLeagueSearchTime(String appRefer);
        public void postLeagueSearchList(String appRefer, String gtype,String showtype, String sorttype,String date);
        public void postLeaguePassSearchList(String appRefer, String gtype,String showtype, String sorttype,String date);
        public void postLeagueSearchChampionList(String appRefer, String showtype,String FStype, String mtype);
        public void postLeagueDetailSearchList(String appRefer, String type,String more, String gid);
    }
    public interface View extends IView<LeagueSearchListContract.Presenter>,IMessageView,IProgressView
    {
        public void postLeagueSearchTimeResult(LeagueSearchTimeResult leagueSearchTimeResult);
        public void postLeagueSearchListResult(LeagueSearchListResult leagueSearchListResult);
        public void postLeagueSearchListNoDataResult(String message);
        public void postLeagueDetailSearchListResult(LeagueDetailSearchListResult leagueDetailSearchListResult);
    }

}
