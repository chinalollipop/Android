package com.hgapp.betnew.depositpage.companypay;

import com.hgapp.betnew.base.IMessageView;
import com.hgapp.betnew.base.IPresenter;
import com.hgapp.betnew.base.IProgressView;
import com.hgapp.betnew.base.IView;

public interface CompanyPayContract {

    public interface Presenter extends IPresenter
    {
        public void postDepositCompanyPaySubimt(String appRefer, String payid, String v_Name, String InType, String v_amount, String cn_date, String memo, String IntoBank);
    }
    public interface View extends IView<Presenter>,IMessageView,IProgressView
    {

    }

}
