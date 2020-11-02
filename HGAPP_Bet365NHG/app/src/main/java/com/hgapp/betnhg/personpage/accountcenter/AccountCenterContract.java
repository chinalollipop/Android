package com.hgapp.betnhg.personpage.accountcenter;

import com.hgapp.betnhg.base.IMessageView;
import com.hgapp.betnhg.base.IPresenter;
import com.hgapp.betnhg.base.IProgressView;
import com.hgapp.betnhg.base.IView;
import com.hgapp.betnhg.data.BetRecordResult;

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
