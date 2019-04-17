package com.cfcp.a01.ui.home.deposit;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.DepositMethodResult;
import com.cfcp.a01.data.DepositTypeResult;
import com.cfcp.a01.data.LoginResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface DepositContract {

    interface Presenter extends IPresenter {

        void getDepositMethod(String appRefer);
        void getDepositVerify(String amount,String deposit_mode,String payment_platform_id);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getDepositMethodResult(DepositMethodResult depositMethodResult);
        void getDepositVerifyResult(DepositTypeResult depositTypeResult);
    }
}
