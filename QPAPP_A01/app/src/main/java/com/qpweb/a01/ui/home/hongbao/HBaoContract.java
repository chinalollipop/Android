package com.qpweb.a01.ui.home.hongbao;

import com.qpweb.a01.base.IMessageView;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.base.IView;
import com.qpweb.a01.data.RedPacketResult;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface HBaoContract {

    public interface Presenter extends IPresenter {
        public void postChangLoginPwd(String appRefer, String type, String pwdCur, String pwdNew, String pwdNew1);
        public void postChangeWithDrawPwd(String appRefer, String type, String nameReal, String pwdSafe, String pwdSafe1);
    }

    public interface View extends IView<Presenter>, IMessageView {

        public void postChangLoginPwdResult(RedPacketResult redPacketResult);
    }
}
