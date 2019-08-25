package com.hg3366.a3366.login.resetpwd;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;

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
