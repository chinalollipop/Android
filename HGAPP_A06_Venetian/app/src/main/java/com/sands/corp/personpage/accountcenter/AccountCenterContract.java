package com.sands.corp.personpage.accountcenter;

import com.sands.corp.base.IMessageView;
import com.sands.corp.base.IPresenter;
import com.sands.corp.base.IProgressView;
import com.sands.corp.base.IView;
import com.sands.corp.data.BetRecordResult;

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
