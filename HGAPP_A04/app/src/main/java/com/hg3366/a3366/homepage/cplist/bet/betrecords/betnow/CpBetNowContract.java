package com.hg3366.a3366.homepage.cplist.bet.betrecords.betnow;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.CPBetNowResult;

public interface CpBetNowContract {
    public interface Presenter extends IPresenter{
        public void getCpBetRecords(String dataTime);
    }

    public interface View extends IView<CpBetNowContract.Presenter>,IMessageView,IProgressView {
        public void getBetRecordsResult(CPBetNowResult betRecordsResult);
    }
}
