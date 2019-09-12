package com.venen.tian.login.fastregister;

import com.venen.tian.base.IMessageView;
import com.venen.tian.base.IPresenter;
import com.venen.tian.base.IProgressView;
import com.venen.tian.base.IView;
import com.venen.tian.data.LoginResult;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface RegisterContract {

    public interface Presenter extends IPresenter
    {


        public void postRegisterMember(String appRefer,String introducer,String keys,String username,String password, String password2,String alias,
                                   String paypassword,String phone,String wechat,String birthday,String know_site);
    }

    public interface View extends IView<Presenter> ,IMessageView,IProgressView
    {

        public void postRegisterMemberResult(LoginResult loginResult);
    }
}
