package com.hgapp.bet365.homepage.cplist.bet.betrecords.betnow;

import com.hgapp.bet365.base.IMessageView;
import com.hgapp.bet365.base.IPresenter;
import com.hgapp.bet365.base.IProgressView;
import com.hgapp.bet365.base.IView;
import com.hgapp.bet365.data.CPBetNowResult;

public interface CpBetNowContract {
    public interface Presenter extends IPresenter{
        public void getCpBetRecords(String dataTime);
    }

    public interface View extends IView<CpBetNowContract.Presenter>,IMessageView,IProgressView {
        public void getBetRecordsResult(CPBetNowResult betRecordsResult);
    }
}
