package com.hfcp.hf.ui.home.bet;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.AllGamesResult;
import com.hfcp.hf.data.BetDataResult;
import com.hfcp.hf.data.BetGameSettingsForRefreshResult;
import com.hfcp.hf.data.GamesTipsResult;

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
