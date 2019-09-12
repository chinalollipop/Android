package com.venen.tian.depositpage.companypay;

import com.venen.tian.base.IMessageView;
import com.venen.tian.base.IPresenter;
import com.venen.tian.base.IProgressView;
import com.venen.tian.base.IView;

public interface CompanyPayContract {

    public interface Presenter extends IPresenter
    {
        public void postDepositCompanyPaySubimt(String appRefer,String payid,String v_Name,String InType,String v_amount,String cn_date,String memo,String IntoBank);
    }
    public interface View extends IView<Presenter>,IMessageView,IProgressView
    {

    }

}
