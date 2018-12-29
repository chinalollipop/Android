package com.qpweb.a01.ui.home.fastlogout;

import com.qpweb.a01.base.IMessageView;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.base.IView;
import com.qpweb.a01.data.LoginResult;

/**
 * Created by Daniel on 2019/1/8.
 */

public interface LogoutContract {

    public interface Presenter extends IPresenter {

        public void postLogout(String appRefer);
    }

    public interface View extends IView<Presenter>, IMessageView {

        public void postLogoutResult(String logoutResult);
    }
}
