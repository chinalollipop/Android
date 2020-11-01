package com.hgapp.betnew.depositpage.aliqcpay;

import com.hgapp.betnew.base.IMessageView;
import com.hgapp.betnew.base.IPresenter;
import com.hgapp.betnew.base.IProgressView;
import com.hgapp.betnew.base.IView;

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
