package com.cfcp.a01.ui.home.dragon;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.BetDragonResult;
import com.cfcp.a01.data.BetRecordsResult;
import com.cfcp.a01.data.CPBetResult;
import com.cfcp.a01.data.TeamReportResult;

import java.util.Map;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface DragonContract {

    interface Presenter extends IPresenter {
        void postCpBets(String game_code, String round, String totalNums, String totalMoney, String number, Map<String, String> fields, String x_session_token);
        void getDragonBetList(String current_password, String new_password);
        void getDragonBetRecordList(String current_password, String new_password);
    }

    interface View extends IView<Presenter>, IMessageView {
        void postCpBetResult(CPBetResult betResult);
        void getDragonBetListResult(BetDragonResult betDragonResult);
        void getDragonBetRecordListResult(BetRecordsResult betRecordsResult);
    }
}
