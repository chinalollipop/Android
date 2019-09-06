package com.gmcp.gm.ui.home;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.AllGamesResult;
import com.gmcp.gm.data.BannerResult;
import com.gmcp.gm.data.GameQueueMoneyResult;
import com.gmcp.gm.data.NoticeResult;

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
