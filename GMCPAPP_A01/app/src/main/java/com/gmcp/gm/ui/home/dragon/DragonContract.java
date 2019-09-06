package com.gmcp.gm.ui.home.dragon;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.BetDragonResult;
import com.gmcp.gm.data.BetRecordsResult;
import com.gmcp.gm.data.CPBetResult;

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
