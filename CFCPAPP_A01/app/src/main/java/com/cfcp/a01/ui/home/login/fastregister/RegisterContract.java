package com.cfcp.a01.ui.home.login.fastregister;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.LoginResult;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface RegisterContract {

    interface Presenter extends IPresenter {


        void postRegisterMember(String username, String password, String password2);
    }

    interface View extends IView<Presenter>, IMessageView {

        void postRegisterMemberResult(LoginResult loginResult);
    }
}
