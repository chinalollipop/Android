package com.nhg.xhg.homepage.handicap.leaguelist;

import com.nhg.xhg.base.IMessageView;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.base.IProgressView;
import com.nhg.xhg.base.IView;
import com.nhg.xhg.data.LeagueDetailSearchListResult;
import com.nhg.xhg.data.LeagueSearchListResult;
import com.nhg.xhg.data.LeagueSearchTimeResult;
import com.nhg.xhg.data.MaintainResult;

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
