package com.cfcp.a01.ui.me.game;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.GameQueueMoneyResult;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.data.LowerInfoDataResult;

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
