package com.hgapp.a6668.login.fastregister;

import com.hgapp.a6668.base.IMessageView;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.base.IProgressView;
import com.hgapp.a6668.base.IView;
import com.hgapp.a6668.data.LoginResult;

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
