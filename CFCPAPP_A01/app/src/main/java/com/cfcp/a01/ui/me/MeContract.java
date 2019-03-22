package com.cfcp.a01.ui.me;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.BalanceResult;
import com.cfcp.a01.data.LogoutResult;

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
