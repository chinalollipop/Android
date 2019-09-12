package com.sands.corp.depositpage.aliqcpay;

import com.sands.corp.base.IMessageView;
import com.sands.corp.base.IPresenter;
import com.sands.corp.base.IProgressView;
import com.sands.corp.base.IView;

public interface AliQCPayContract {

    public interface Presenter extends IPresenter
    {
        public void postDepositAliPayQCPaySubimt(String appRefer, String payid,  String v_amount, String cn_date, String memo,String bank_user);
    }
    public interface View extends IView<Presenter>,IMessageView,IProgressView
    {
        public void postDepositAliPayQCPaySubimtResult(String  message);
    }

}
