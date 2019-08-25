package com.hg3366.a3366.homepage.cplist.order;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.COLastResultHK;
import com.hg3366.a3366.data.CPBJSCResult;
import com.hg3366.a3366.data.CPHKResult;
import com.hg3366.a3366.data.CPJSFTResult;
import com.hg3366.a3366.data.CPJSK2Result;
import com.hg3366.a3366.data.CPJSKSResult;
import com.hg3366.a3366.data.CPJSSCResult;
import com.hg3366.a3366.data.CPLastResult;
import com.hg3366.a3366.data.CPLeftInfoResult;
import com.hg3366.a3366.data.CPNextIssueResult;
import com.hg3366.a3366.data.CPQuickBetResult;
import com.hg3366.a3366.data.CPXYNCResult;
import com.hg3366.a3366.data.CQ1FCResult;
import com.hg3366.a3366.data.CQ2FCResult;
import com.hg3366.a3366.data.CQ3FCResult;
import com.hg3366.a3366.data.CQ5FCResult;
import com.hg3366.a3366.data.CQSSCResult;
import com.hg3366.a3366.data.PCDDResult;

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
