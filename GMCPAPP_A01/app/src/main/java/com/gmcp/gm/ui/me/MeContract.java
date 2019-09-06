package com.gmcp.gm.ui.me;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.BalanceResult;
import com.gmcp.gm.data.LogoutResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface MeContract {

    interface Presenter extends IPresenter {

        void postLogout(String appRefer);
        void getBalance();
    }

    interface View extends IView<Presenter>, IMessageView {

        void postLogoutResult(LogoutResult logoutResult);
        void getBalanceResult(BalanceResult balanceResult);
    }
}
