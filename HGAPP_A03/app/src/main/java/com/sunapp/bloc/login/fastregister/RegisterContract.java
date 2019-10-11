package com.sunapp.bloc.login.fastregister;

import com.sunapp.bloc.base.IMessageView;
import com.sunapp.bloc.base.IPresenter;
import com.sunapp.bloc.base.IProgressView;
import com.sunapp.bloc.base.IView;
import com.sunapp.bloc.data.LoginResult;

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
