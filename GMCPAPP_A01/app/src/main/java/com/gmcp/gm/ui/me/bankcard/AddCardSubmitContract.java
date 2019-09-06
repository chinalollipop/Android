package com.gmcp.gm.ui.me.bankcard;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.BankCardAddResult;

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
