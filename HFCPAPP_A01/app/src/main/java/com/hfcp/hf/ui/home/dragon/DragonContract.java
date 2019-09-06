package com.hfcp.hf.ui.home.dragon;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.BetDragonResult;
import com.hfcp.hf.data.BetRecordsResult;
import com.hfcp.hf.data.CPBetResult;

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
