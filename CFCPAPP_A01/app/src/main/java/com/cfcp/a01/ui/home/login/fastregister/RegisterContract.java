package com.cfcp.a01.ui.home.login.fastregister;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.LoginResult;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface RegisterContract {

    public interface Presenter extends IPresenter {


        public void postRegisterMember(String appRefer, String action, String reference, String username,
                                       String password, String password2, String verifycode,
                                       String code);
    }

    public interface View extends IView<Presenter>, IMessageView {

        public void postRegisterMemberResult(LoginResult loginResult);
    }
}
