package com.hfcp.hf.ui.home.cplist.bet.betrecords.betnow;


import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.CPBetNowResult;

public interface CpBetNowContract {
    public interface Presenter extends IPresenter {
        public void getCpBetRecords(String dataTime);
    }

    public interface View extends IView<Presenter>, IMessageView {
        public void getBetRecordsResult(CPBetNowResult betRecordsResult);
    }
}
