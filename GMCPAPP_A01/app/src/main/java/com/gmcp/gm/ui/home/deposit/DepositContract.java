package com.gmcp.gm.ui.home.deposit;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.DepositMethodResult;
import com.gmcp.gm.data.DepositTypeResult;

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
