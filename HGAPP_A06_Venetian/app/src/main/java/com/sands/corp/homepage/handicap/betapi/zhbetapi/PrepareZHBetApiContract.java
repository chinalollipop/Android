package com.sands.corp.homepage.handicap.betapi.zhbetapi;

import com.sands.corp.base.IMessageView;
import com.sands.corp.base.IPresenter;
import com.sands.corp.base.IProgressView;
import com.sands.corp.base.IView;
import com.sands.corp.data.BetZHResult;
import com.sands.corp.data.GameAllZHBetsBKResult;

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
