package com.hfcp.hf.ui.home.cplist.order;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.CPBJSCResult;
import com.hfcp.hf.data.CPHKResult;
import com.hfcp.hf.data.CPJSFTResult;
import com.hfcp.hf.data.CPJSK2Result;
import com.hfcp.hf.data.CPJSKSResult;
import com.hfcp.hf.data.CPJSSCResult;
import com.hfcp.hf.data.CPKL8Result;
import com.hfcp.hf.data.CPKLSFResult;
import com.hfcp.hf.data.CPLastResult;
import com.hfcp.hf.data.CPLeftInfoResult;
import com.hfcp.hf.data.CPNextIssueResult;
import com.hfcp.hf.data.CPQuickBetResult;
import com.hfcp.hf.data.CPXYNCResult;
import com.hfcp.hf.data.CQ1FCResult;
import com.hfcp.hf.data.CQ2FCResult;
import com.hfcp.hf.data.CQ3FCResult;
import com.hfcp.hf.data.CQ5FCResult;
import com.hfcp.hf.data.CQSSCResult;
import com.hfcp.hf.data.Cp11X5Result;
import com.hfcp.hf.data.GamesTipsResult;
import com.hfcp.hf.data.PCDDResult;

public interface CPOrderContract {

    public interface Presenter extends IPresenter {

        public void postCPLeftInfo(String type, String x_session_token);

        public void postQuickBet(String game_code, String type, String x_session_token);

        public void postRateInfoBjsc(String lottery_id, String type, String x_session_token);

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

        public void postRateInfoKl8(String game_code, String type, String x_session_token);

        public void postRateInfo11X5(String game_code, String type, String x_session_token);

        public void postRateInfoHK(String game_code, String type, String x_session_token);

        public void postRateInfoPCDD(String game_code, String x_session_token);

        /*public void postRateInfo6(String game_code,String type,String x_session_token);
        public void postRateInfo1(String game_code,String type,String x_session_token);*/
        public void postLastResult(String game_code, String x_session_token);

        public void postNextIssue(String lottery_id, String x_session_token);

        void getGamesTips();

    }

    public interface View extends IView<Presenter>, IMessageView {
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

        public void postNextIssueResult(CPNextIssueResult cpNextIssueResult);

        public void postCPLeftInfoResult(CPLeftInfoResult cpLeftInfoResult);

        public void postRateInfoPCDDResult(PCDDResult pcddResult);

        public void postRateInfoJsk3Result(CPJSKSResult cpjsksResult);

        public void postRateInfoJsk32Result(CPJSK2Result cpjsk2Result);

        public void postRateInfoXyncResult(CPXYNCResult cpxyncResult);

        public void postRateInfoKlsfResult(CPKLSFResult cpklsfResult);

        public void postRateInfoKl8Result(CPKL8Result cpkl8Result);

        public void postRateInfo11X5Result(Cp11X5Result cpxyncResult);

        public void postRateInfoHKResult(CPHKResult cphkResult);

        void setGamesTipsResult(GamesTipsResult gamesTipsResult);
    }

}
