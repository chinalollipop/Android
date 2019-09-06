package com.hfcp.hf.ui.me;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.BalanceResult;
import com.hfcp.hf.data.LogoutResult;

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
