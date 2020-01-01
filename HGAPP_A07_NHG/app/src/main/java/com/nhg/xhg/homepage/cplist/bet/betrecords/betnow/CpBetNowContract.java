package com.nhg.xhg.homepage.cplist.bet.betrecords.betnow;

import com.nhg.xhg.base.IMessageView;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.base.IProgressView;
import com.nhg.xhg.base.IView;
import com.nhg.xhg.data.CPBetNowResult;

public interface CpBetNowContract {
    public interface Presenter extends IPresenter{
        public void getCpBetRecords(String dataTime);
    }

    public interface View extends IView<CpBetNowContract.Presenter>,IMessageView,IProgressView {
        public void getBetRecordsResult(CPBetNowResult betRecordsResult);
    }
}
