package com.cfcp.a01.ui.home.cplist.bet.betrecords.betnow;


import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.CPBetNowResult;

public interface CpBetNowContract {
    public interface Presenter extends IPresenter {
        public void getCpBetRecords(String dataTime);
    }

    public interface View extends IView<Presenter>, IMessageView {
        public void getBetRecordsResult(CPBetNowResult betRecordsResult);
    }
}
