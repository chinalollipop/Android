package com.hg3366.a3366.login.fastregister;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.LoginResult;

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
