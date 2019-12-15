package com.hgapp.a6668.homepage.handicap.leaguedetail.zhbet;

import com.hgapp.a6668.base.IMessageView;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.base.IProgressView;
import com.hgapp.a6668.base.IView;
import com.hgapp.a6668.data.GameAllPlayZHResult;

public interface PrepareBetZHApiContract {
    public interface Presenter extends IPresenter{

        public void postGameAllBetsZH(String appRefer, String gtype, String sorttype, String mdate, String showtype,String M_League, String gid);
    }

    public interface View extends IView<PrepareBetZHApiContract.Presenter>,IMessageView,IProgressView {
        public void postGameAllBetsZH(GameAllPlayZHResult gameAllPlayZHResult);
        public void postGameAllBetsZHFailResult(String message);
    }
}
