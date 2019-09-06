package com.cfcp.a01.ui.home;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.AllGamesResult;
import com.cfcp.a01.data.BannerResult;
import com.cfcp.a01.data.GameQueueMoneyResult;
import com.cfcp.a01.data.NoticeResult;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface HomeContract {
    interface Presenter extends IPresenter {
        void getBanner(String appRefer);
        void getNotice(String appRefer);
        void getAllGames(String appRefer);
        void getAllGamesNew(String appRefer);
        void postLogout(String appRefer);
        void getJointLogin(String username);
        void getKaiYuanGame(String username);
        void getAGGames(String username);
        void getAGVideoGames(String username);
        void getAGFishGames(String username);
        void getPlayOutWithMoney(String action);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getBannerResult(BannerResult bannerResult);
        void getNoticeResult(NoticeResult noticeResult);
        void getAllGamesResult(AllGamesResult allGamesResult);
        void getAllGamesNewResult(AllGamesResult allGamesResult);
        void postLogoutResult(String logoutResult);
        void getJointLoginResult(String logoutResult);
        void getAGGamesResult(AllGamesResult allGamesResult);
        void getAGVideoGamesResult(AllGamesResult allGamesResult);
        void getAGFishGamesResult(AllGamesResult allGamesResult);
        void getPlayOutWithMoneyResult(GameQueueMoneyResult gameQueueMoneyResult);
    }
}
