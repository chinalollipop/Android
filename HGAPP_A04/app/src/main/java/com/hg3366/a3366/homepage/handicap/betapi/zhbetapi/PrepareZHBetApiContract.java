package com.hg3366.a3366.homepage.handicap.betapi.zhbetapi;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.BetZHResult;
import com.hg3366.a3366.data.GameAllZHBetsBKResult;

public interface PrepareZHBetApiContract {
    public interface Presenter extends IPresenter{
        public void postGameAllZHBetsBK(String appRefer, String game, String game_id);
        public void postGameAllZHBetsFT(String appRefer, String game, String game_id);
        public void postZHBetBK(String appRefer, String active, String teamcount, String gold, String wagerDatas);
        public void postZHBetFT(String appRefer, String active, String teamcount, String gold, String wagerDatas);
    }

    public interface View extends IView<PrepareZHBetApiContract.Presenter>,IMessageView,IProgressView {
        public void postGameAllZHBetsBKResult(GameAllZHBetsBKResult gameAllZHBetsBKResult);
        public void postGameAllZHBetsFTResult(GameAllZHBetsBKResult gameAllZHBetsBKResult);
        public void postZHBetFTResult(BetZHResult betResult);
        public void postBetApiFailResult(String message);
    }
}
