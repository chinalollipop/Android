package com.hgapp.betnhg.depositpage.companypay;

import com.hgapp.betnhg.base.IMessageView;
import com.hgapp.betnhg.base.IPresenter;
import com.hgapp.betnhg.base.IProgressView;
import com.hgapp.betnhg.base.IView;

public interface CompanyPayContract {

    public interface Presenter extends IPresenter
    {
        public void postDepositCompanyPaySubimt(String appRefer, String payid, String v_Name, String InType, String v_amount, String cn_date, String memo, String IntoBank);
    }
    public interface View extends IView<Presenter>,IMessageView,IProgressView
    {

    }

}
