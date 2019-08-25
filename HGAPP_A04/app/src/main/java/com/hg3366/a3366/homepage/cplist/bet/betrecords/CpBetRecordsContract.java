package com.hg3366.a3366.homepage.cplist.bet.betrecords;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.BetRecordsResult;

public interface CpBetRecordsContract {
    public interface Presenter extends IPresenter{
        public void getCpBetRecords();
    }

    public interface View extends IView<CpBetRecordsContract.Presenter>,IMessageView,IProgressView {
        public void getBetRecordsResult(BetRecordsResult betRecordsResult);
    }
}
