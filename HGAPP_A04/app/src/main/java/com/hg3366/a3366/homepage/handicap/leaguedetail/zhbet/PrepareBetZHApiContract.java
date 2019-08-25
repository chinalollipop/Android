package com.hg3366.a3366.homepage.handicap.leaguedetail.zhbet;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.GameAllPlayZHResult;

public interface PrepareBetZHApiContract {
    public interface Presenter extends IPresenter{

        public void postGameAllBetsZH(String appRefer, String gtype, String sorttype, String mdate, String showtype,String M_League, String gid);
    }

    public interface View extends IView<PrepareBetZHApiContract.Presenter>,IMessageView,IProgressView {
        public void postGameAllBetsZH(GameAllPlayZHResult gameAllPlayZHResult);
        public void postGameAllBetsZHFailResult(String message);
    }
}
