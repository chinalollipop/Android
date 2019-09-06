package com.hfcp.hf.ui.home.cplist.bet.betrecords.chonglong;


import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.CPChangLongResult;

public interface CpChangLongContract {
    public interface Presenter extends IPresenter {
        public void getCpBetRecords(String dataTime);
    }

    public interface View extends IView<Presenter>, IMessageView {
        public void getBetRecordsResult(CPChangLongResult cpChangLongResult);
    }
}
