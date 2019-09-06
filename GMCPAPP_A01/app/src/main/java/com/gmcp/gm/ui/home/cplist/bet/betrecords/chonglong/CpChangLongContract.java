package com.gmcp.gm.ui.home.cplist.bet.betrecords.chonglong;


import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.CPChangLongResult;

public interface CpChangLongContract {
    public interface Presenter extends IPresenter {
        public void getCpBetRecords(String dataTime);
    }

    public interface View extends IView<Presenter>, IMessageView {
        public void getBetRecordsResult(CPChangLongResult cpChangLongResult);
    }
}
