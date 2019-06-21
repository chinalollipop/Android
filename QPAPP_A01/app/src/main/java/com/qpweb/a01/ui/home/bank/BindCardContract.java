package com.qpweb.a01.ui.home.bank;

import com.qpweb.a01.base.IMessageView;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.base.IView;
import com.qpweb.a01.data.BankListResult;
import com.qpweb.a01.data.BindCardResult;
import com.qpweb.a01.data.RedPacketResult;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface BindCardContract {

    public interface Presenter extends IPresenter {
        public void postBankList(String appRefer, String mem_phone);
        public void postBindBank(String appRefer, String real_name, String bank_Account, String bank_Address,String bank_Id);
    }

    public interface View extends IView<Presenter>, IMessageView {

        public void postBankListResult(BankListResult bankListResult);
        public void postBindBankResult(BindCardResult bindCardResult);
    }
}
