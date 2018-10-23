package com.hgapp.a0086.login.forgetpwd;

import com.hgapp.a0086.base.IMessageView;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.base.IProgressView;
import com.hgapp.a0086.base.IView;
import com.hgapp.a0086.data.LoginResult;

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
