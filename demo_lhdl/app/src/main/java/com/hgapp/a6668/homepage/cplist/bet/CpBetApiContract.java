package com.hgapp.a6668.homepage.cplist.bet;

import com.hgapp.a6668.base.IMessageView;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.base.IProgressView;
import com.hgapp.a6668.base.IView;
import com.hgapp.a6668.data.BetResult;
import com.hgapp.a6668.data.CPBetResult;

import java.util.Map;

public interface CpBetApiContract {
    public interface Presenter extends IPresenter{
        public void postCpBets(String game_code, String round, String totalNums, String totalMoney, String number, Map<String, String> fields, String x_session_token);
        public void postCpBetsHK(String game_code, String round, String totalNums, String totalMoney, String number,String betmoney,String typecode,String rtype, String x_session_token);
        public void postCpBetsHKMap(String game_code, String round, String totalNums, String totalMoney, String number,Map<String, String> fields,String x_session_token);
        public void postCpBetsLM(String game_code, String round, String totalNums, String totalMoney, String number,String betmoney,String typecode, String x_session_token);

    }

    public interface View extends IView<CpBetApiContract.Presenter>,IMessageView,IProgressView {
        public void postCpBetResult(CPBetResult betResult);
    }
}
