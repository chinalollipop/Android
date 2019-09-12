package com.sands.corp.login.fastlogin;

import com.sands.corp.base.IMessageView;
import com.sands.corp.base.IPresenter;
import com.sands.corp.base.IProgressView;
import com.sands.corp.base.IView;
import com.sands.corp.data.LoginResult;
import com.sands.corp.data.SportsPlayMethodRBResult;

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
    }

    public interface View extends IView<Presenter> ,IMessageView,IProgressView
    {
        public void successGet(LoginResult loginResult);
        public void postLoginResult(LoginResult loginResult);
        public void postLoginResultError(String message);
        public void success(SportsPlayMethodRBResult fullPayGameResult);
    }
}
