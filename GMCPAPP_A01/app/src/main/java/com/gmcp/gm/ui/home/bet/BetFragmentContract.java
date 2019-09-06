package com.gmcp.gm.ui.home.bet;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.AllGamesResult;
import com.gmcp.gm.data.BetDataResult;
import com.gmcp.gm.data.BetGameSettingsForRefreshResult;
import com.gmcp.gm.data.GamesTipsResult;

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
