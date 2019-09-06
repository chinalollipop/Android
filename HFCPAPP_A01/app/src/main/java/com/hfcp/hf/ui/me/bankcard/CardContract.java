package com.hfcp.hf.ui.me.bankcard;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.BankCardListResult;

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
