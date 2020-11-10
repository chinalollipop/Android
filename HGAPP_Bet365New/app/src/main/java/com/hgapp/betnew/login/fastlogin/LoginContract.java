package com.hgapp.betnew.login.fastlogin;

import com.hgapp.betnew.base.IMessageView;
import com.hgapp.betnew.base.IPresenter;
import com.hgapp.betnew.base.IProgressView;
import com.hgapp.betnew.base.IView;
import com.hgapp.betnew.data.LoginResult;
import com.hgapp.betnew.data.SportsPlayMethodRBResult;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface LoginContract {

    public interface Presenter extends IPresenter
    {
        public void postLogin(String appRefer,String username, String passwd);
        public void postLoginDemo(String appRefer,String phone,String username, String passwd);
        public void loginGet();
        public void logOut();
        public void getFullPayGameList();
        public void postFullPayGameList();//String appRefer,String type, String more
        public void addMember();
        public void postRegisterMember(String appRefer,String introducer,String keys,String username,String password, String password2,String alias,
                                       String paypassword,String phone,String wechat,String qq,String know_site);
    }

    public interface View extends IView<Presenter> ,IMessageView,IProgressView
    {
        public void successGet(LoginResult loginResult);
        public void postLoginResult(LoginResult loginResult);
        public void postLoginResultError(String message);
        public void success(SportsPlayMethodRBResult fullPayGameResult);
        public void postRegisterMemberResult(LoginResult loginResult);
    }
}