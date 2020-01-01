package com.nhg.xhg.login.fastlogin;

import com.nhg.xhg.base.IMessageView;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.base.IProgressView;
import com.nhg.xhg.base.IView;
import com.nhg.xhg.data.LoginResult;
import com.nhg.xhg.data.SportsPlayMethodRBResult;

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
    }

    public interface View extends IView<Presenter> ,IMessageView,IProgressView
    {
        public void successGet(LoginResult loginResult);
        public void postLoginResult(LoginResult loginResult);
        public void postLoginResultError(String message);
        public void success(SportsPlayMethodRBResult fullPayGameResult);
    }
}
