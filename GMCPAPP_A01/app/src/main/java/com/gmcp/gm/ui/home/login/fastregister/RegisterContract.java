package com.gmcp.gm.ui.home.login.fastregister;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.LoginResult;

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
