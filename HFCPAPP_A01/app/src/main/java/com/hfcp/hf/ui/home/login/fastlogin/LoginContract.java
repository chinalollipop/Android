package com.hfcp.hf.ui.home.login.fastlogin;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.LoginResult;

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
