package com.hgapp.a0086.personpage.accountcenter;

import com.hgapp.a0086.base.IMessageView;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.base.IProgressView;
import com.hgapp.a0086.base.IView;
import com.hgapp.a0086.data.BetRecordResult;

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
