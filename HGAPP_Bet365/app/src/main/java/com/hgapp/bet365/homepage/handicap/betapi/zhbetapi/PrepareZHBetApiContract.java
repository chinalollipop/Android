package com.hgapp.bet365.homepage.handicap.betapi.zhbetapi;

import com.hgapp.bet365.base.IMessageView;
import com.hgapp.bet365.base.IPresenter;
import com.hgapp.bet365.base.IProgressView;
import com.hgapp.bet365.base.IView;
import com.hgapp.bet365.data.BetZHResult;
import com.hgapp.bet365.data.GameAllZHBetsBKResult;

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