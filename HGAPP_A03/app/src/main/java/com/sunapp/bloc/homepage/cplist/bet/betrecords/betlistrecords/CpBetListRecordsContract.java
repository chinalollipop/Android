package com.sunapp.bloc.homepage.cplist.bet.betrecords.betlistrecords;

import com.sunapp.bloc.base.IMessageView;
import com.sunapp.bloc.base.IPresenter;
import com.sunapp.bloc.base.IProgressView;
import com.sunapp.bloc.base.IView;
import com.sunapp.bloc.data.BetRecordsListItemResult;

public interface CpBetListRecordsContract {
    public interface Presenter extends IPresenter{
        public void getCpBetRecords(String dataTime,String from);
    }

    public interface View extends IView<CpBetListRecordsContract.Presenter>,IMessageView,IProgressView {
        public void getBetRecordsResult(BetRecordsListItemResult betRecordsResult);
    }
}
