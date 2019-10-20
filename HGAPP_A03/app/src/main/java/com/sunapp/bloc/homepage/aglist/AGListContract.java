package com.sunapp.bloc.homepage.aglist;

import com.sunapp.bloc.base.IMessageView;
import com.sunapp.bloc.base.IPresenter;
import com.sunapp.bloc.base.IProgressView;
import com.sunapp.bloc.base.IView;
import com.sunapp.bloc.data.AGGameLoginResult;
import com.sunapp.bloc.data.AGLiveResult;
import com.sunapp.bloc.data.CheckAgLiveResult;
import com.sunapp.bloc.data.PersonBalanceResult;

import java.util.List;

public interface AGListContract {

    public interface Presenter extends IPresenter
    {
        public void postPersonBalance(String appRefer, String action);
        public void postMGPersonBalance(String appRefer, String action);
        public void postCQPersonBalance(String appRefer, String action);
        public void postMWPersonBalance(String appRefer, String action);
        public void postAGGameList(String appRefer, String uid, String action);
        public void postMGGameList(String appRefer, String uid, String action);
        public void postMWGameList(String appRefer, String uid, String action);
        public void postCQGameList(String appRefer, String uid, String action);
        public void postCheckAgLiveAccount(String appRefer);
        public void postCheckAgGameAccount(String appRefer);
        public void postGoPlayGame(String appRefer, String gameid);
        public void postGoPlayGameMG(String appRefer, String gameid);
        public void postGoPlayGameCQ(String appRefer, String gameid);
        public void postCheckAgAccount(String appRefer, String uid, String action);
        public void postCreateAgAccount(String appRefer, String uid, String action);
    }
    public interface View extends IView<AGListContract.Presenter>,IMessageView,IProgressView
    {
        public void postGoPlayGameResult(AGGameLoginResult agGameLoginResult);
        public void postCheckAgLiveAccountResult(CheckAgLiveResult checkAgLiveResult);
        public void postCheckAgGameAccountResult(CheckAgLiveResult checkAgLiveResult);
        public void postPersonBalanceResult(PersonBalanceResult personBalance);
        public void postMGPersonBalanceResult(PersonBalanceResult personBalance);
        public void postCQPersonBalanceResult(PersonBalanceResult personBalance);
        public void postMWPersonBalanceResult(PersonBalanceResult personBalance);
        public void postAGGameResult(List<AGLiveResult> agLiveResult);
        public void postCheckAgAccountResult(CheckAgLiveResult checkAgLiveResult);
        public void postCreateAgAccountResult(CheckAgLiveResult checkAgLiveResult);
    }

}
