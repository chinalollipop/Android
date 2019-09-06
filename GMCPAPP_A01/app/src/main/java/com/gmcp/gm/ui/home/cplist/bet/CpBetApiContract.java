package com.gmcp.gm.ui.home.cplist.bet;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.CPBetResult;

import java.util.Map;

public interface CpBetApiContract {
    public interface Presenter extends IPresenter{
        public void postCpBets(String game_code, String round, String totalNums, String totalMoney, String number, Map<String, String> fields, String x_session_token);
        public void postCpBetsHK(String game_code, String round, String totalNums, String totalMoney, String number, String betmoney, String typecode, String rtype, String x_session_token);
        public void postCpBetsHKMap(String game_code, String round, String totalNums, String totalMoney, String number, Map<String, String> fields, String x_session_token);
        public void postCpBetsLM(String game_code, String round, String totalNums, String totalMoney, String number, String betmoney, String typecode, String x_session_token);

    }

    public interface View extends IView<CpBetApiContract.Presenter>,IMessageView {
        public void postCpBetResult(CPBetResult betResult);
    }
}
