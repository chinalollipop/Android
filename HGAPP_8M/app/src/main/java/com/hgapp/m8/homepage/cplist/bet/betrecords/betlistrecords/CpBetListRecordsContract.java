package com.hgapp.m8.homepage.cplist.bet.betrecords.betlistrecords;

import com.hgapp.m8.base.IMessageView;
import com.hgapp.m8.base.IPresenter;
import com.hgapp.m8.base.IProgressView;
import com.hgapp.m8.base.IView;
import com.hgapp.m8.data.BetRecordsListItemResult;

public interface CpBetListRecordsContract {
    public interface Presenter extends IPresenter{
        public void getCpBetRecords(String dataTime,String from);
    }

    public interface View extends IView<CpBetListRecordsContract.Presenter>,IMessageView,IProgressView {
        public void getBetRecordsResult(BetRecordsListItemResult betRecordsResult);
    }
}
