package com.venen.tian.homepage.handicap.leaguelist.championlist;

import com.venen.tian.base.IMessageView;
import com.venen.tian.base.IPresenter;
import com.venen.tian.base.IProgressView;
import com.venen.tian.base.IView;
import com.venen.tian.data.ChampionDetailListResult;
import com.venen.tian.data.PrepareBetResult;

public interface ChampionDetailListContract {

    public interface Presenter extends IPresenter
    {
        public void postLeagueSearchChampionList(String appRefer, String showtype,String FStype, String mtype, String M_League);
        public void postPrepareBetApi(String appRefer, String order_method, String gid, String type, String wtype, String rtype, String odd_f_type, String error_flag, String order_type);
    }
    public interface View extends IView<ChampionDetailListContract.Presenter>,IMessageView,IProgressView
    {
        public void postLeagueSearchChampionListResult(ChampionDetailListResult championDetailListResult);
        public void postLeagueSearchChampionListNoDataResult(String noDataString);
        public void postPrepareBetApiResult(PrepareBetResult prepareBetResult);
    }

}
