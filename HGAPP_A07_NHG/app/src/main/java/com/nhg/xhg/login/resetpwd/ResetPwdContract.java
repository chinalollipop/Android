package com.nhg.xhg.login.resetpwd;

import com.nhg.xhg.base.IMessageView;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.base.IProgressView;
import com.nhg.xhg.base.IView;

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
