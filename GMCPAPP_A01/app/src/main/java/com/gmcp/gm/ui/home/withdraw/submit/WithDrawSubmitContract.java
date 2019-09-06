package com.gmcp.gm.ui.home.withdraw.submit;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.WithDrawNextResult;

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
