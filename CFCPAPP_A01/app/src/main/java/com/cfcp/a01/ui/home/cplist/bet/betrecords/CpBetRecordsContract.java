package com.cfcp.a01.ui.home.cplist.bet.betrecords;


import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.BetRecordsResult;

public interface CpBetRecordsContract {
    public interface Presenter extends IPresenter {
        public void getCpBetRecords();
    }

    public interface View extends IView<Presenter>, IMessageView {
        public void getBetRecordsResult(BetRecordsResult betRecordsResult);
    }
}