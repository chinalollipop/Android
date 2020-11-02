package com.hgapp.betnhg.login.forgetpwd;

import com.hgapp.betnhg.base.IMessageView;
import com.hgapp.betnhg.base.IPresenter;
import com.hgapp.betnhg.base.IProgressView;
import com.hgapp.betnhg.base.IView;
import com.hgapp.betnhg.data.LoginResult;

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
