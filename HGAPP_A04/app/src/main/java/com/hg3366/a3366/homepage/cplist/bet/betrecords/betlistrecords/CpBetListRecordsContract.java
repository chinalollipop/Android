package com.hg3366.a3366.homepage.cplist.bet.betrecords.betlistrecords;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.BetRecordsListItemResult;

public interface CpBetListRecordsContract {
    public interface Presenter extends IPresenter{
        public void getCpBetRecords(String dataTime,String from);
    }

    public interface View extends IView<CpBetListRecordsContract.Presenter>,IMessageView,IProgressView {
        public void getBetRecordsResult(BetRecordsListItemResult betRecordsResult);
    }
}
