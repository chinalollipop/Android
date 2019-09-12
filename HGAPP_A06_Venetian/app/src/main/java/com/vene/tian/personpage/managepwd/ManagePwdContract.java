package com.vene.tian.personpage.managepwd;

import com.vene.tian.base.IMessageView;
import com.vene.tian.base.IPresenter;
import com.vene.tian.base.IProgressView;
import com.vene.tian.base.IView;


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
