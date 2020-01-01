package com.nhg.xhg.depositpage.aliqcpay;

import com.nhg.xhg.base.IMessageView;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.base.IProgressView;
import com.nhg.xhg.base.IView;

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
