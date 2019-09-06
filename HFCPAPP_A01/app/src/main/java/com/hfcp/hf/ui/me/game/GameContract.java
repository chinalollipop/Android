package com.hfcp.hf.ui.me.game;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.GameQueueMoneyResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface GameContract {

    interface Presenter extends IPresenter {

        void getLowerLevelReport(String action);
        void getPlayOutWithMoney(String action);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getPlayOutWithMoneyResult(GameQueueMoneyResult gameQueueMoneyResult);
        void getLowerLevelReportResult(GameQueueMoneyResult gameQueueMoneyResult);
    }
}
