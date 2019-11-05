package com.hg3366.a3366.homepage.aglist;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.AGGameLoginResult;
import com.hg3366.a3366.data.AGLiveResult;
import com.hg3366.a3366.data.CheckAgLiveResult;
import com.hg3366.a3366.data.PersonBalanceResult;

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
        public void postGoPlayGameMW(String appRefer, String gameid);
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
        public void postsMessageGameResult(String message);
        public void postCheckAgAccountResult(CheckAgLiveResult checkAgLiveResult);
        public void postCreateAgAccountResult(CheckAgLiveResult checkAgLiveResult);
    }

}
