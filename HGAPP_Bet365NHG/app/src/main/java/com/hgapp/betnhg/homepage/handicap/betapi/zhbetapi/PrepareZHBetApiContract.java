package com.hgapp.betnhg.homepage.handicap.betapi.zhbetapi;

import com.hgapp.betnhg.base.IMessageView;
import com.hgapp.betnhg.base.IPresenter;
import com.hgapp.betnhg.base.IProgressView;
import com.hgapp.betnhg.base.IView;
import com.hgapp.betnhg.data.BetZHResult;
import com.hgapp.betnhg.data.GameAllZHBetsBKResult;
import com.hgapp.betnhg.data.PersonInformResult;

public interface PrepareZHBetApiContract {
    public interface Presenter extends IPresenter{
        public void postGameAllZHBetsBK(String appRefer, String game, String game_id);
        public void postGameAllZHBetsFT(String appRefer, String game, String game_id);
        public void postZHBetBK(String appRefer, String active, String teamcount, String gold, String wagerDatas);
        public void postZHBetFT(String appRefer, String active, String teamcount, String gold, String wagerDatas);
        public void getPersonInform(String appRefer);
    }

    public interface View extends IView<PrepareZHBetApiContract.Presenter>,IMessageView,IProgressView {
        public void postGameAllZHBetsBKResult(GameAllZHBetsBKResult gameAllZHBetsBKResult);
        public void postGameAllZHBetsFTResult(GameAllZHBetsBKResult gameAllZHBetsBKResult);
        public void postZHBetFTResult(BetZHResult betResult);
        public void postBetApiFailResult(String message);
        public void postPersonInformResult(PersonInformResult personInformResult);
    }
}
