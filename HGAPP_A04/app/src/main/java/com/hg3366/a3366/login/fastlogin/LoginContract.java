package com.hg3366.a3366.login.fastlogin;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.LoginResult;
import com.hg3366.a3366.data.SportsPlayMethodRBResult;

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
                                       String paypassword,String phone,String wechat,String birthday,String know_site);
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
