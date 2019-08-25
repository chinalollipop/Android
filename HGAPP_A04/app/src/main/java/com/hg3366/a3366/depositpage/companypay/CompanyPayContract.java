package com.hg3366.a3366.depositpage.companypay;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;

public interface CompanyPayContract {

    public interface Presenter extends IPresenter
    {
        public void postDepositCompanyPaySubimt(String appRefer,String payid,String v_Name,String InType,String v_amount,String cn_date,String memo,String IntoBank);
    }
    public interface View extends IView<Presenter>,IMessageView,IProgressView
    {

    }

}
