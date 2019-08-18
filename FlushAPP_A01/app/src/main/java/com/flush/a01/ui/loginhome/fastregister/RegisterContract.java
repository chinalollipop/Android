package com.flush.a01.ui.loginhome.fastregister;

import com.flush.a01.base.IMessageView;
import com.flush.a01.base.IPresenter;
import com.flush.a01.base.IView;
import com.flush.a01.data.LoginResult;

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
