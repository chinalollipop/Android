package com.sands.corp.homepage.sportslist.bet;

import com.sands.corp.base.IMessageView;
import com.sands.corp.base.IPresenter;
import com.sands.corp.base.IProgressView;
import com.sands.corp.base.IView;
import com.sands.corp.data.BetResult;
import com.sands.corp.data.SportsPlayMethodResult;

public interface BetContract {
    public interface Presenter extends IPresenter
    {
        public void postSportsPlayMethod(String appRefer, String type, String more, String gid);
        public void postSportsPlayRBMethod(String appRefer, String type, String more, String gid);
        public void postBet(String appRefer, String cate, String gid, String type, String active, String line_type, String odd_f_type, String gold, String ioradio_r_h, String rtype, String wtype);
    }

    public interface View extends IView<BetContract.Presenter>,IMessageView,IProgressView {

        public void postSportsPlayMethodResult(SportsPlayMethodResult sportsPlayMethodResult);
       // public void postSportsPlayMethodRBResult(SportsPlayMethodRBResult sportsPlayMethodRBResult);
        public void postBetResult(BetResult betResult);
    }
}
