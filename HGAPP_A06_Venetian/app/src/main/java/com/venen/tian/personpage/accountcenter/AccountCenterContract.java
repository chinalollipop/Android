package com.venen.tian.personpage.accountcenter;

import com.venen.tian.base.IMessageView;
import com.venen.tian.base.IPresenter;
import com.venen.tian.base.IProgressView;
import com.venen.tian.base.IView;
import com.venen.tian.data.BetRecordResult;

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
