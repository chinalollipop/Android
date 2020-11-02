package com.hgapp.betnhg.homepage.cplist.order;

import com.hgapp.betnhg.base.IMessageView;
import com.hgapp.betnhg.base.IPresenter;
import com.hgapp.betnhg.base.IProgressView;
import com.hgapp.betnhg.base.IView;
import com.hgapp.betnhg.data.COLastResultHK;
import com.hgapp.betnhg.data.CPBJSCResult;
import com.hgapp.betnhg.data.CPHKResult;
import com.hgapp.betnhg.data.CPJSFTResult;
import com.hgapp.betnhg.data.CPJSK2Result;
import com.hgapp.betnhg.data.CPJSKSResult;
import com.hgapp.betnhg.data.CPJSSCResult;
import com.hgapp.betnhg.data.CPLastResult;
import com.hgapp.betnhg.data.CPLeftInfoResult;
import com.hgapp.betnhg.data.CPNextIssueResult;
import com.hgapp.betnhg.data.CPQuickBetResult;
import com.hgapp.betnhg.data.CPXYNCResult;
import com.hgapp.betnhg.data.CQ1FCResult;
import com.hgapp.betnhg.data.CQ2FCResult;
import com.hgapp.betnhg.data.CQ3FCResult;
import com.hgapp.betnhg.data.CQ5FCResult;
import com.hgapp.betnhg.data.CQSSCResult;
import com.hgapp.betnhg.data.PCDDResult;

public interface CPOrderContract {

    public interface Presenter extends IPresenter
    {

        public void postCPLeftInfo(String type, String x_session_token);
        public void postQuickBet(String game_code, String type, String x_session_token);
        public void postRateInfoBjsc(String game_code, String type, String x_session_token);
        public void postRateInfoJssc(String game_code, String type, String x_session_token);
        public void postRateInfoJsft(String game_code, String type, String x_session_token);
        public void postRateInfo(String game_code, String type, String x_session_token);
        public void postRateInfo1FC(String game_code, String type, String x_session_token);
        public void postRateInfo2FC(String game_code, String type, String x_session_token);
        public void postRateInfo3FC(String game_code, String type, String x_session_token);
        public void postRateInfo5FC(String game_code, String type, String x_session_token);
        public void postRateInfoJsk3(String game_code, String type, String x_session_token);
        public void postRateInfoJsk32(String game_code, String type, String x_session_token);
        public void postRateInfoXync(String game_code, String type, String x_session_token);
        public void postRateInfoKlsf(String game_code, String type, String x_session_token);
        public void postRateInfoHK(String game_code, String type, String x_session_token);
        public void postRateInfoPCDD(String game_code, String x_session_token);
        /*public void postRateInfo6(String game_code,String type,String x_session_token);
        public void postRateInfo1(String game_code,String type,String x_session_token);*/
        public void postLastResult(String game_code, String x_session_token);
        public void postLastResultHK(String game_code, String x_session_token);
        public void postNextIssue(String game_code, String x_session_token);
        public void postNextIssueHK(String game_code, String x_session_token);

    }
    public interface View extends IView<CPOrderContract.Presenter>,IMessageView,IProgressView
    {
        public void postRateInfoResult(CQSSCResult cqsscResult);
        public void postQuickBetResult(CPQuickBetResult cpQuickBetResult);
        public void postRateInfoBjscResult(CPBJSCResult cpbjscResult);
        public void postRateInfoJsscResult(CPJSSCResult cpbjscResult);
        public void postRateInfoJsftResult(CPJSFTResult cpbjscResult);
        public void postRateInfo1FCResult(CQ1FCResult cqffcResult);
        public void postRateInfo2FCResult(CQ2FCResult cqffcResult);
        public void postRateInfo3FCResult(CQ3FCResult cqffcResult);
        public void postRateInfo5FCResult(CQ5FCResult cqffcResult);
       /* public void postRateInfo6Result(CQSSCResult cqsscResult);
        public void postRateInfo1Result(CQSSCResult cqsscResult);*/
        public void postLastResultResult(CPLastResult cpLastResult);
        public void postLastResultHKResult(COLastResultHK coLastResultHK);
        public void postNextIssueResult(CPNextIssueResult cpNextIssueResult);
        public void postCPLeftInfoResult(CPLeftInfoResult cpLeftInfoResult);
        public void postRateInfoPCDDResult(PCDDResult pcddResult);
        public void postRateInfoJsk3Result(CPJSKSResult cpjsksResult);
        public void postRateInfoJsk32Result(CPJSK2Result cpjsk2Result);
        public void postRateInfoXyncResult(CPXYNCResult cpxyncResult);
        public void postRateInfoKlsfResult(CPXYNCResult cpxyncResult);
        public void postRateInfoHKResult(CPHKResult cphkResult);
    }

}
