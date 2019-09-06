package com.hfcp.hf.ui.home.login.fastregister;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.LoginResult;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface RegisterContract {

    interface Presenter extends IPresenter {


        void postRegisterMember(String agent,String username, String password, String password2, String qq);
    }

    interface View extends IView<Presenter>, IMessageView {

        void postRegisterMemberResult(LoginResult loginResult);
    }
}
