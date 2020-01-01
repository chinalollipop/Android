package com.nhg.xhg.homepage.cplist.order;

import com.nhg.xhg.base.IMessageView;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.base.IProgressView;
import com.nhg.xhg.base.IView;
import com.nhg.xhg.data.COLastResultHK;
import com.nhg.xhg.data.CPBJSCResult;
import com.nhg.xhg.data.CPHKResult;
import com.nhg.xhg.data.CPJSFTResult;
import com.nhg.xhg.data.CPJSK2Result;
import com.nhg.xhg.data.CPJSKSResult;
import com.nhg.xhg.data.CPJSSCResult;
import com.nhg.xhg.data.CPLastResult;
import com.nhg.xhg.data.CPLeftInfoResult;
import com.nhg.xhg.data.CPNextIssueResult;
import com.nhg.xhg.data.CPQuickBetResult;
import com.nhg.xhg.data.CPXYNCResult;
import com.nhg.xhg.data.CQ1FCResult;
import com.nhg.xhg.data.CQ2FCResult;
import com.nhg.xhg.data.CQ3FCResult;
import com.nhg.xhg.data.CQ5FCResult;
import com.nhg.xhg.data.CQSSCResult;
import com.nhg.xhg.data.PCDDResult;

public interface CPOrderContract {

    public interface Presenter extends IPresenter
    {

        public void postCPLeftInfo(String type,String x_session_token);
        public void postQuickBet(String game_code,String type,String x_session_token);
        public void postRateInfoBjsc(String game_code,String type,String x_session_token);
        public void postRateInfoJssc(String game_code,String type,String x_session_token);
        public void postRateInfoJsft(String game_code,String type,String x_session_token);
        public void postRateInfo(String game_code,String type,String x_session_token);
        public void postRateInfo1FC(String game_code,String type,String x_session_token);
        public void postRateInfo2FC(String game_code,String type,String x_session_token);
        public void postRateInfo3FC(String game_code,String type,String x_session_token);
        public void postRateInfo5FC(String game_code,String type,String x_session_token);
        public void postRateInfoJsk3(String game_code,String type,String x_session_token);
        public void postRateInfoJsk32(String game_code,String type,String x_session_token);
        public void postRateInfoXync(String game_code,String type,String x_session_token);
        public void postRateInfoKlsf(String game_code,String type,String x_session_token);
        public void postRateInfoHK(String game_code,String type,String x_session_token);
        public void postRateInfoPCDD(String game_code,String x_session_token);
        /*public void postRateInfo6(String game_code,String type,String x_session_token);
        public void postRateInfo1(String game_code,String type,String x_session_token);*/
        public void postLastResult(String game_code,String x_session_token);
        public void postLastResultHK(String game_code,String x_session_token);
        public void postNextIssue(String game_code,String x_session_token);
        public void postNextIssueHK(String game_code,String x_session_token);

    }
    public interface View extends IView<CPOrderContract.Presenter>,IMessageView,IProgressView
    {
        public void postRateInfoResult(CQSSCResult cqsscResult);
        public void postQuickBetResult(CPQuickBetResult cpQuickBetResult);
        public void postRateInfoBjscResult(CPBJSCResult cpbjscResult);
        public void postRateInfoJsscResult(CPJSSCResult cpbjscResult );
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
