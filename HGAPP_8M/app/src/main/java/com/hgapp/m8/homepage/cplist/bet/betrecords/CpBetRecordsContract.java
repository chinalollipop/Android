package com.hgapp.m8.homepage.cplist.bet.betrecords;

import com.hgapp.m8.base.IMessageView;
import com.hgapp.m8.base.IPresenter;
import com.hgapp.m8.base.IProgressView;
import com.hgapp.m8.base.IView;
import com.hgapp.m8.data.BetRecordsResult;

public interface CpBetRecordsContract {
    public interface Presenter extends IPresenter{
        public void getCpBetRecords();
    }

    public interface View extends IView<CpBetRecordsContract.Presenter>,IMessageView,IProgressView {
        public void getBetRecordsResult(BetRecordsResult betRecordsResult);
    }
}
