package com.sands.corp.depositpage.companypay;

import com.sands.corp.base.IMessageView;
import com.sands.corp.base.IPresenter;
import com.sands.corp.base.IProgressView;
import com.sands.corp.base.IView;

public interface CompanyPayContract {

    public interface Presenter extends IPresenter
    {
        public void postDepositCompanyPaySubimt(String appRefer,String payid,String v_Name,String InType,String v_amount,String cn_date,String memo,String IntoBank);
    }
    public interface View extends IView<Presenter>,IMessageView,IProgressView
    {

    }

}
