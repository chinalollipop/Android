package com.cfcp.a01.ui.me.bankcard;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.BankCardListResult;
import com.cfcp.a01.data.LoginResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface CardContract {

    interface Presenter extends IPresenter {
        void getBankCardList();
        void getDeleteCard(String id);
    }

    interface View extends IView<Presenter>, IMessageView {
        void getBankCardListResult(BankCardListResult bankCardListResult);
        void getDeleteCardResult();
    }
}
