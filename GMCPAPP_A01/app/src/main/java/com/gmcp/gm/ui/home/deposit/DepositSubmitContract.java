package com.gmcp.gm.ui.home.deposit;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.DepositH5Result;

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
