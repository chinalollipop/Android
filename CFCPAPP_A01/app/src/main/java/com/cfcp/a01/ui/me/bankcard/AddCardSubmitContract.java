package com.cfcp.a01.ui.me.bankcard;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.BankCardAddResult;
import com.cfcp.a01.data.BankListResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface AddCardSubmitContract {

    interface Presenter extends IPresenter {

        void getAddCardSubmit(String type,String id,String bank,String bank_id,String branch,String account_name,String account,String account_confirmation);
        void getModifyCardClearSession(String id);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getAddCardSubmitResult(BankCardAddResult bankCardAddResult);
        void getModifyCardClearSession();
    }
}
