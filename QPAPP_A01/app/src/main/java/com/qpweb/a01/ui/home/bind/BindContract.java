package com.qpweb.a01.ui.home.bind;

import com.qpweb.a01.base.IMessageView;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.base.IView;
import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.data.RedPacketResult;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface BindContract {

    public interface Presenter extends IPresenter {
        public void postSendCode(String appRefer, String mem_phone);
        public void postCodeSubmit(String appRefer,String nickName, String mem_phone, String mem_yzm);
    }

    public interface View extends IView<Presenter>, IMessageView {

        public void postSendCodeResult();
        public void postCodeSubmitResult(RedPacketResult redPacketResult);
    }
}
