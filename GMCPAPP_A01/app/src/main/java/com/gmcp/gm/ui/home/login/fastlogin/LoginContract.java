package com.gmcp.gm.ui.home.login.fastlogin;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.LoginResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface LoginContract {

    interface Presenter extends IPresenter {

        void postLogin(String appRefer, String username, String password);
        void postDemo(String appRefer, String username, String password);
    }

    interface View extends IView<Presenter>, IMessageView {

        void postLoginResult(LoginResult loginResult);
    }
}
