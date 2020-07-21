package com.hgapp.bet365.login.forgetpwd;

import com.hgapp.bet365.base.IMessageView;
import com.hgapp.bet365.base.IPresenter;
import com.hgapp.bet365.base.IProgressView;
import com.hgapp.bet365.base.IView;
import com.hgapp.bet365.data.LoginResult;

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
