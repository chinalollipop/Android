package com.cfcp.a01.ui.home.fastlogout;

import com.cfcp.a01.base.IMessageView;
import com.cfcp.a01.base.IPresenter;
import com.cfcp.a01.base.IView;

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
