package com.gmcp.gm.ui.me.game;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.GameQueueMoneyResult;

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
