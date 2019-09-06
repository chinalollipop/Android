package com.hfcp.hf.ui.home.deposit;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.DepositMethodResult;
import com.hfcp.hf.data.DepositTypeResult;

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
