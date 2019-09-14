package com.sands.corp.homepage.cplist.bet.betrecords;

import com.sands.corp.base.IMessageView;
import com.sands.corp.base.IPresenter;
import com.sands.corp.base.IProgressView;
import com.sands.corp.base.IView;
import com.sands.corp.data.BetRecordsResult;

public interface CpBetRecordsContract {
    public interface Presenter extends IPresenter{
        public void getCpBetRecords();
    }

    public interface View extends IView<CpBetRecordsContract.Presenter>,IMessageView,IProgressView {
        public void getBetRecordsResult(BetRecordsResult betRecordsResult);
    }
}