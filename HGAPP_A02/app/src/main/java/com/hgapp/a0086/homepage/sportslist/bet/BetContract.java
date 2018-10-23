package com.hgapp.a0086.homepage.sportslist.bet;

import com.hgapp.a0086.base.IMessageView;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.base.IProgressView;
import com.hgapp.a0086.base.IView;
import com.hgapp.a0086.data.BetResult;
import com.hgapp.a0086.data.SportsPlayMethodResult;

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
