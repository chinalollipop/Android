package com.cfcp.a01.ui.me;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.data.LogoutResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface MeContract {

    public interface Presenter extends IPresenter {

        public void postLogout(String appRefer);
    }

    public interface View extends IView<Presenter>, IMessageView {

        public void postLogoutResult(LogoutResult logoutResult);
    }
}
