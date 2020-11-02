package com.hgapp.betnhg.homepage.cplist.bet.betrecords.betnow;

import com.hgapp.betnhg.base.IMessageView;
import com.hgapp.betnhg.base.IPresenter;
import com.hgapp.betnhg.base.IProgressView;
import com.hgapp.betnhg.base.IView;
import com.hgapp.betnhg.data.CPBetNowResult;

public interface CpBetNowContract {
    public interface Presenter extends IPresenter{
        public void getCpBetRecords(String dataTime);
    }

    public interface View extends IView<CpBetNowContract.Presenter>,IMessageView,IProgressView {
        public void getBetRecordsResult(CPBetNowResult betRecordsResult);
    }
}
