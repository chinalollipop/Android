package com.hfcp.hf.ui.home.deposit;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.DepositH5Result;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface DepositSubmitContract {

    interface Presenter extends IPresenter {

        void getDepositSubmit(String deposit_mode, String payment_platform_id,String payer_name,String amount);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getDepositSubmitResult(DepositH5Result depositH5Result);
    }
}
