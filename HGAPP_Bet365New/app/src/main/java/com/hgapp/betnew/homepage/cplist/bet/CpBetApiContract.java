package com.hgapp.betnew.homepage.cplist.bet;

import com.hgapp.betnew.base.IMessageView;
import com.hgapp.betnew.base.IPresenter;
import com.hgapp.betnew.base.IProgressView;
import com.hgapp.betnew.base.IView;
import com.hgapp.betnew.data.CPBetResult;

import java.util.Map;

public interface CpBetApiContract {
    public interface Presenter extends IPresenter{
        public void postCpBets(String game_code, String round, String totalNums, String totalMoney, String number, Map<String, String> fields, String x_session_token);
        public void postCpBetsHK(String game_code, String round, String totalNums, String totalMoney, String number, String betmoney, String typecode, String rtype, String x_session_token);
        public void postCpBetsHKMap(String game_code, String round, String totalNums, String totalMoney, String number, Map<String, String> fields, String x_session_token);
        public void postCpBetsLM(String game_code, String round, String totalNums, String totalMoney, String number, String betmoney, String typecode, String x_session_token);

    }

    public interface View extends IView<CpBetApiContract.Presenter>,IMessageView,IProgressView {
        public void postCpBetResult(CPBetResult betResult);
    }
}
