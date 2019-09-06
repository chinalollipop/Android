package com.cfcp.a01.ui.home.bet;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.AllGamesResult;
import com.cfcp.a01.data.BetDataResult;
import com.cfcp.a01.data.BetGameSettingsForRefreshResult;
import com.cfcp.a01.data.GamesTipsResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface BetFragmentContract {

    interface Presenter extends IPresenter {
        void getGameSettingsForRefresh(int id, boolean isRefresh);

        void getAllGames();

        void getBet(int id, String betdata);

        void getGamesTips();
    }

    interface View extends IView<Presenter>, IMessageView {
        void setGameSettingsForRefreshResult(BetGameSettingsForRefreshResult betGameSettingsForRefreshResult, boolean isRefresh);

        void setAllGamesResult(AllGamesResult allGamesResult);

        void setBetResult(BetDataResult betDataResult);

        void setGamesTipsResult(GamesTipsResult gamesTipsResult);
    }
}
