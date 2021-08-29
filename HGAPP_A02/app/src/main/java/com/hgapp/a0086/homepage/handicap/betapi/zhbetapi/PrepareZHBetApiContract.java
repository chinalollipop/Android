package com.hgapp.a0086.homepage.handicap.betapi.zhbetapi;

import com.hgapp.a0086.base.IMessageView;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.base.IProgressView;
import com.hgapp.a0086.base.IView;
import com.hgapp.a0086.data.BetZHResult;
import com.hgapp.a0086.data.GameAllZHBetsBKResult;

public interface PrepareZHBetApiContract {
    public interface Presenter extends IPresenter{
        public void postGameAllZHBetsBK(String appRefer, String game, String game_id, String gid_fs);
        public void postGameAllZHBetsFT(String appRefer, String game, String game_id, String gid_fs);
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
