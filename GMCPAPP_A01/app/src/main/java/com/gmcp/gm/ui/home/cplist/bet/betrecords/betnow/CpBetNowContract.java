package com.gmcp.gm.ui.home.cplist.bet.betrecords.betnow;


import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.CPBetNowResult;

public interface CpBetNowContract {
    public interface Presenter extends IPresenter {
        public void getCpBetRecords(String dataTime);
    }

    public interface View extends IView<Presenter>, IMessageView {
        public void getBetRecordsResult(CPBetNowResult betRecordsResult);
    }
}
