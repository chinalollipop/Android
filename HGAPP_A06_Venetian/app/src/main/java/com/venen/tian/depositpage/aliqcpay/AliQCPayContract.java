package com.venen.tian.depositpage.aliqcpay;

import com.venen.tian.base.IMessageView;
import com.venen.tian.base.IPresenter;
import com.venen.tian.base.IProgressView;
import com.venen.tian.base.IView;

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
