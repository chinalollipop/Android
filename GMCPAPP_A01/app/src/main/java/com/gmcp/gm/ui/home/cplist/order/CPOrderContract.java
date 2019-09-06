package com.gmcp.gm.ui.home.cplist.order;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.CPBJSCResult;
import com.gmcp.gm.data.CPHKResult;
import com.gmcp.gm.data.CPJSFTResult;
import com.gmcp.gm.data.CPJSK2Result;
import com.gmcp.gm.data.CPJSKSResult;
import com.gmcp.gm.data.CPJSSCResult;
import com.gmcp.gm.data.CPKL8Result;
import com.gmcp.gm.data.CPKLSFResult;
import com.gmcp.gm.data.CPLastResult;
import com.gmcp.gm.data.CPLeftInfoResult;
import com.gmcp.gm.data.CPNextIssueResult;
import com.gmcp.gm.data.CPQuickBetResult;
import com.gmcp.gm.data.CPXYNCResult;
import com.gmcp.gm.data.CQ1FCResult;
import com.gmcp.gm.data.CQ2FCResult;
import com.gmcp.gm.data.CQ3FCResult;
import com.gmcp.gm.data.CQ5FCResult;
import com.gmcp.gm.data.CQSSCResult;
import com.gmcp.gm.data.Cp11X5Result;
import com.gmcp.gm.data.GamesTipsResult;
import com.gmcp.gm.data.PCDDResult;

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
