package com.cfcp.a01.ui.home.deposit;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.DepositH5Result;
import com.cfcp.a01.data.DepositMethodResult;
import com.cfcp.a01.data.LoginResult;

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
