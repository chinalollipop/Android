package com.cfcp.a01.ui.me.bankcard;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.BankCardAddResult;
import com.cfcp.a01.data.BankCardListResult;
import com.cfcp.a01.data.BankListResult;
import com.cfcp.a01.data.TeamReportResult;

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
