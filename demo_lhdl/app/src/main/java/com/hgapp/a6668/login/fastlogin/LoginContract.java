package com.hgapp.a6668.login.fastlogin;

import com.hgapp.a6668.base.IMessageView;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.base.IProgressView;
import com.hgapp.a6668.base.IView;
import com.hgapp.a6668.data.CPResult;
import com.hgapp.a6668.data.LoginResult;
import com.hgapp.a6668.data.SportsPlayMethodRBResult;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface LoginContract {

    public interface Presenter extends IPresenter
    {
        public void postLogin(String appRefer,String username, String passwd);
        public void postLoginDemo(String appRefer,String username, String passwd);
        public void loginGet();
        public void logOut();
        public void getFullPayGameList();
        public void postFullPayGameList();//String appRefer,String type, String more
        public void addMember();
        public void postCP();
    }

    public interface View extends IView<Presenter> ,IMessageView,IProgressView
    {
        public void postCPResult(CPResult cpResult);
        public void successGet(LoginResult loginResult);
        public void postLoginResult(LoginResult loginResult);
        public void success(SportsPlayMethodRBResult fullPayGameResult);
    }
}
