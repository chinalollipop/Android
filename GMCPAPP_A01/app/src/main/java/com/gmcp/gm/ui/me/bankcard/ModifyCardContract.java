package com.gmcp.gm.ui.me.bankcard;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.BankCardAddResult;
import com.gmcp.gm.data.BankListResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface ModifyCardContract {

    interface Presenter extends IPresenter {

        void getBankList(String id);
        void getModifyCard(String id,String bank,String bank_id,String branch,String account_name,String account,String account_confirmation);
    }

    interface View extends IView<Presenter>, IMessageView {
        void getBankListResult(BankListResult bankListResult);
        void getModifyCardResult(BankCardAddResult bankCardAddResult);
    }
}
