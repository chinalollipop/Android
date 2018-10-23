package com.hgapp.a6668.depositpage.aliqcpay;

import com.hgapp.a6668.base.IMessageView;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.base.IProgressView;
import com.hgapp.a6668.base.IView;

public interface AliQCPayContract {

    public interface Presenter extends IPresenter
    {
        public void postDepositAliPayQCPaySubimt(String appRefer, String payid,  String v_amount, String cn_date, String memo,String bank_user);
    }
    public interface View extends IView<Presenter>,IMessageView,IProgressView
    {

    }

}
