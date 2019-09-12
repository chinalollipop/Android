package com.sands.corp.homepage.handicap.leaguedetail.zhbet;

import com.sands.corp.base.IMessageView;
import com.sands.corp.base.IPresenter;
import com.sands.corp.base.IProgressView;
import com.sands.corp.base.IView;
import com.sands.corp.data.GameAllPlayZHResult;

public interface PrepareBetZHApiContract {
    public interface Presenter extends IPresenter{

        public void postGameAllBetsZH(String appRefer, String gtype, String sorttype, String mdate, String showtype,String M_League, String gid);
    }

    public interface View extends IView<PrepareBetZHApiContract.Presenter>,IMessageView,IProgressView {
        public void postGameAllBetsZH(GameAllPlayZHResult gameAllPlayZHResult);
        public void postGameAllBetsZHFailResult(String message);
    }
}
