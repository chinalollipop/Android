package com.hgapp.m8.personpage.managepwd;

import com.hgapp.m8.base.IMessageView;
import com.hgapp.m8.base.IPresenter;
import com.hgapp.m8.base.IProgressView;
import com.hgapp.m8.base.IView;


public interface ManagePwdContract {
    public interface Presenter extends IPresenter
    {
        public void getChangeLoginPwd(String appRefer, String action,String flag_action,String oldpassword,String password, String REpassword);
        public void getChangeWithdrawPwd(String appRefer,String action,String flag_action, String pay_oldpassword,String pay_password, String pay_REpassword);
    }
    public interface View extends IView<ManagePwdContract.Presenter>,IMessageView,IProgressView
    {
        public void onChangeLoginPwdResut(String  successMessage);

    }
}
