package com.hgapp.a6668.homepage.cplist.bet.betrecords;

import com.hgapp.a6668.base.IMessageView;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.base.IProgressView;
import com.hgapp.a6668.base.IView;
import com.hgapp.a6668.data.BetRecordsResult;
import com.hgapp.a6668.data.CPBetResult;

import java.util.Map;

public interface CpBetRecordsContract {
    public interface Presenter extends IPresenter{
        public void getCpBetRecords();
    }

    public interface View extends IView<CpBetRecordsContract.Presenter>,IMessageView,IProgressView {
        public void getBetRecordsResult(BetRecordsResult betRecordsResult);
    }
}
