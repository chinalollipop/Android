package com.vene.tian.login.forgetpwd;

import com.vene.tian.base.IMessageView;
import com.vene.tian.base.IPresenter;
import com.vene.tian.base.IProgressView;
import com.vene.tian.base.IView;
import com.vene.tian.data.LoginResult;

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
