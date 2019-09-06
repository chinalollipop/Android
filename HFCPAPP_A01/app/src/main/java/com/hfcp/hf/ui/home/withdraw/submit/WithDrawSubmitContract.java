package com.hfcp.hf.ui.home.withdraw.submit;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.WithDrawNextResult;

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
