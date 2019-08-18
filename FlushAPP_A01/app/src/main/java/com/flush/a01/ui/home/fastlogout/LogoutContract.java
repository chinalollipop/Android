package com.flush.a01.ui.home.fastlogout;

import com.flush.a01.base.IMessageView;
import com.flush.a01.base.IPresenter;
import com.flush.a01.base.IView;

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
