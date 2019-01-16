package com.cfcp.a01.ui.login.fastlogin;

import com.cfcp.a01.base.IMessageView;
import com.cfcp.a01.base.IPresenter;
import com.cfcp.a01.base.IView;
import com.cfcp.a01.data.LoginResult;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface LoginContract {

    public interface Presenter extends IPresenter {

        public void postLogin(String appRefer, String username, String password);
    }

    public interface View extends IView<Presenter>, IMessageView {

        public void postLoginResult(LoginResult loginResult);
    }
}
