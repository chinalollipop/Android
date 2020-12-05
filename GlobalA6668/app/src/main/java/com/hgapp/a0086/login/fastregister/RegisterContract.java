package com.hgapp.a0086.login.fastregister;

import com.hgapp.a0086.base.IMessageView;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.base.IProgressView;
import com.hgapp.a0086.base.IView;
import com.hgapp.a0086.data.LoginResult;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface RegisterContract {

    public interface Presenter extends IPresenter
    {


        public void postRegisterMember(String appRefer,String introducer,String keys,String username,String password, String password2,String alias,
                                   String paypassword,String phone,String wechat,String birthday,String know_site,String code);
    }

    public interface View extends IView<Presenter> ,IMessageView,IProgressView
    {

        public void postRegisterMemberResult(LoginResult loginResult);
    }
}
