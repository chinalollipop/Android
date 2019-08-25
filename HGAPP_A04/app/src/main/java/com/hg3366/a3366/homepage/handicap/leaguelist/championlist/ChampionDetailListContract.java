package com.hg3366.a3366.homepage.handicap.leaguelist.championlist;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.ChampionDetailListResult;
import com.hg3366.a3366.data.PrepareBetResult;

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
