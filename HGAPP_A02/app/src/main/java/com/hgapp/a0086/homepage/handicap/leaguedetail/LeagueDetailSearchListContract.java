package com.hgapp.a0086.homepage.handicap.leaguedetail;

import com.hgapp.a0086.base.IMessageView;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.base.IProgressView;
import com.hgapp.a0086.base.IView;
import com.hgapp.a0086.data.BetResult;
import com.hgapp.a0086.data.ComPassSearchListResult;
import com.hgapp.a0086.data.GameAllPlayBKResult;
import com.hgapp.a0086.data.GameAllPlayRFTResult;
import com.hgapp.a0086.data.LeagueDetailListDataResults;
import com.hgapp.a0086.data.LeagueDetailSearchListResult;
import com.hgapp.a0086.data.PrepareBetResult;

import java.util.List;

public interface LeagueDetailSearchListContract {

    public interface Presenter extends IPresenter
    {
        public void postLeagueDetailSearchList(String appRefer, String type, String more, String gid);
        public void postComPassSearchList(String appRefer, String gtype, String sorttype, String mdate,String showtype, String M_league);
        public void postPrepareBetApi(String appRefer, String order_method, String gid, String type, String wtype, String rtype,String odd_f_type, String error_flag, String order_type,String isMaster);
        public void postBetApi(String appRefer, String cate, String gid, String type, String active, String line_type, String odd_f_type, String gold, String ioradio_r_h, String rtype, String wtype);
        public void postGameAllBets(String appRefer, String gid, String gtype,String showtype,String postion,String action);
    }
    public interface View extends IView<LeagueDetailSearchListContract.Presenter>,IMessageView,IProgressView
    {
        public void postLeagueDetailSearchListResult(LeagueDetailSearchListResult leagueDetailSearchListResult);
        public void postComPassSearchListResult(ComPassSearchListResult leagueDetailSearchListResult);
        public void postLeagueDetailSearchListNoDataResult(String  noDataString);
        public void postPrepareBetApiResult(PrepareBetResult prepareBetResult);
        public void postBetApiResult(BetResult betResult);
        public void postGameAllBetsResult(List<LeagueDetailListDataResults.DataBean> leagueDetailListDataResults,String postion,String action);
    }

}
