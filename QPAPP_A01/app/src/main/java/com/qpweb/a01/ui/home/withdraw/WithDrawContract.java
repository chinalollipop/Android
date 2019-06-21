package com.qpweb.a01.ui.home.withdraw;

import com.qpweb.a01.base.IMessageView;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.base.IView;
import com.qpweb.a01.data.BankListResult;
import com.qpweb.a01.data.BindCardResult;
import com.qpweb.a01.data.MemValidBetResult;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface WithDrawContract {

    public interface Presenter extends IPresenter {
        public void postMemValidBet(String appRefer, String mem_phone);
        public void postWithDraw(String appRefer, String Bank_Address, String Bank_Account, String Bank_Name, String Money, String Withdrawal_Passwd, String Alias);
    }

    public interface View extends IView<Presenter>, IMessageView {

        public void postMemValidBetResult(MemValidBetResult memValidBetResult);
        public void postMemValidBetErrorResult();
        public void postWithDrawResult(BindCardResult bindCardResult);
    }
}
