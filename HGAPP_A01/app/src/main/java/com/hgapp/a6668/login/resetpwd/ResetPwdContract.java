package com.hgapp.a6668.login.resetpwd;

import com.hgapp.a6668.base.IMessageView;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.base.IProgressView;
import com.hgapp.a6668.base.IView;
import com.hgapp.a6668.data.LoginResult;
import com.hgapp.a6668.data.SportsPlayMethodRBResult;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface ResetPwdContract {

    public interface Presenter extends IPresenter
    {
        public void getChangeLoginPwd(String appRefer, String action,String flag_action,String oldpassword,String password, String REpassword);
    }

    public interface View extends IView<Presenter> ,IMessageView,IProgressView
    {
        public void onChangeLoginPwdResut(String  successMessage);
    }
}
