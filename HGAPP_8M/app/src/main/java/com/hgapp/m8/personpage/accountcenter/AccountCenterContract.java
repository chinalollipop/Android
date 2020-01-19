package com.hgapp.m8.personpage.accountcenter;

import com.hgapp.m8.base.IMessageView;
import com.hgapp.m8.base.IPresenter;
import com.hgapp.m8.base.IProgressView;
import com.hgapp.m8.base.IView;
import com.hgapp.m8.data.BetRecordResult;

public interface AccountCenterContract {
    public interface Presenter extends IPresenter
    {
        public void postBetToday(String appRefer, String gtype, String page);

        public void postBetHistory(String appRefer, String gtype, String page);
    }
    public interface View extends IView<AccountCenterContract.Presenter>,IMessageView,IProgressView
    {
        public void postBetRecordResult(BetRecordResult message);
    }
}
