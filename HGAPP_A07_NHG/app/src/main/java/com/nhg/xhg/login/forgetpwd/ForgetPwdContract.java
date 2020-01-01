package com.nhg.xhg.login.forgetpwd;

import com.nhg.xhg.base.IMessageView;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.base.IProgressView;
import com.nhg.xhg.base.IView;
import com.nhg.xhg.data.LoginResult;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface ForgetPwdContract {

    public interface Presenter extends IPresenter
    {
        public void postForgetPwd(String appRefer, String action_type, String username, String realname,String withdraw_password, String birthday, String new_password,String password_confirmation);
    }

    public interface View extends IView<Presenter> ,IMessageView,IProgressView
    {
        public void postForgetPwdResult(LoginResult loginResult);
    }
}
