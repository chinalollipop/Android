package com.venen.tian.homepage.handicap.leaguelist;

import com.venen.tian.base.IMessageView;
import com.venen.tian.base.IPresenter;
import com.venen.tian.base.IProgressView;
import com.venen.tian.base.IView;
import com.venen.tian.data.LeagueDetailSearchListResult;
import com.venen.tian.data.LeagueSearchListResult;
import com.venen.tian.data.LeagueSearchTimeResult;
import com.venen.tian.data.MaintainResult;

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
