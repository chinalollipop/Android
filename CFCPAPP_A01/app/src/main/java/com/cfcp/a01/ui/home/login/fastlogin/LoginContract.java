package com.cfcp.a01.ui.home.login.fastlogin;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.LoginResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface LoginContract {

    public interface Presenter extends IPresenter {

        public void postLogin(String appRefer, String username, String password);
    }

    public interface View extends IView<Presenter>, IMessageView {

        public void postLoginResult(LoginResult loginResult);
    }
}
