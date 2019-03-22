package com.cfcp.a01.ui.home.withdraw.submit;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.WithDrawNextResult;
import com.cfcp.a01.data.WithDrawResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface WithDrawSubmitContract {

    interface Presenter extends IPresenter {

        void getWithDrawSubmit(String id, String amount,String fundPwd);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getWithDrawSubmitResult(WithDrawNextResult withDrawNextResult);
    }
}
