package com.nhg.xhg.homepage.handicap.leaguedetail.zhbet;

import com.nhg.xhg.base.IMessageView;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.base.IProgressView;
import com.nhg.xhg.base.IView;
import com.nhg.xhg.data.GameAllPlayZHResult;

public interface PrepareBetZHApiContract {
    public interface Presenter extends IPresenter{

        public void postGameAllBetsZH(String appRefer, String gtype, String sorttype, String mdate, String showtype,String M_League, String gid);
    }

    public interface View extends IView<PrepareBetZHApiContract.Presenter>,IMessageView,IProgressView {
        public void postGameAllBetsZH(GameAllPlayZHResult gameAllPlayZHResult);
        public void postGameAllBetsZHFailResult(String message);
    }
}
