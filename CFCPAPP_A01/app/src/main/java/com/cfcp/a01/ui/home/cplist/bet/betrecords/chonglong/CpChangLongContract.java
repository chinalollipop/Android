package com.cfcp.a01.ui.home.cplist.bet.betrecords.chonglong;


import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.CPBetNowResult;
import com.cfcp.a01.data.CPChangLongResult;

public interface CpChangLongContract {
    public interface Presenter extends IPresenter {
        public void getCpBetRecords(String dataTime);
    }

    public interface View extends IView<Presenter>, IMessageView {
        public void getBetRecordsResult(CPChangLongResult cpChangLongResult);
    }
}
