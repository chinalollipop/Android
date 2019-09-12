package com.venen.tian.login.resetpwd;

import com.venen.tian.base.IMessageView;
import com.venen.tian.base.IPresenter;
import com.venen.tian.base.IProgressView;
import com.venen.tian.base.IView;

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
