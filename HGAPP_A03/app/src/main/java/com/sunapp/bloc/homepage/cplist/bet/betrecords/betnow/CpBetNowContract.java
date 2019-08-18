package com.sunapp.bloc.homepage.cplist.bet.betrecords.betnow;

import com.sunapp.bloc.base.IMessageView;
import com.sunapp.bloc.base.IPresenter;
import com.sunapp.bloc.base.IProgressView;
import com.sunapp.bloc.base.IView;
import com.sunapp.bloc.data.CPBetNowResult;

public interface CpBetNowContract {
    public interface Presenter extends IPresenter{
        public void getCpBetRecords(String dataTime);
    }

    public interface View extends IView<CpBetNowContract.Presenter>,IMessageView,IProgressView {
        public void getBetRecordsResult(CPBetNowResult betRecordsResult);
    }
}
