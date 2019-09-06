package com.gmcp.gm.ui.me.bankcard;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.BankCardListResult;

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
