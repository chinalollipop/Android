package com.qpweb.a01.ui.loginhome.sign;

import com.qpweb.a01.base.IMessageView;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.base.IView;
import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.data.SignTodayResult;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface SignTodayContract {

    public interface Presenter extends IPresenter {

        public void postSignTodays(String appRefer, String username, String password);
        public void postRed(String appRefer, String username, String password);
    }

    public interface View extends IView<Presenter>, IMessageView {

        public void postSignTodaysResult(SignTodayResult signTodayResult);
        public void postRedResult(RedPacketResult redPacketResult);
    }
}
