package com.venen.tian.personpage.balanceplatform;

import com.venen.tian.base.IMessageView;
import com.venen.tian.base.IPresenter;
import com.venen.tian.base.IProgressView;
import com.venen.tian.base.IView;
import com.venen.tian.data.BetRecordResult;
import com.venen.tian.data.KYBalanceResult;

public interface BalancePlatformContract {
    public interface Presenter extends IPresenter
    {
        public void postPersonBalanceTY(String appRefer,String action);
        public void postBanalceTransferTY(String appRefer, String f, String t, String b);
        public void postBanalceTransfer(String appRefer, String f, String t, String b);
        public void postBanalceTransferKY(String appRefer, String f, String t, String b);
        public void postBanalceTransferHG(String appRefer, String f, String t, String b);
        public void postBanalceTransferVG(String appRefer, String f, String t, String b);
        public void postBanalceTransferLY(String appRefer, String f, String t, String b);
        public void postBanalceTransferMG(String appRefer, String f, String t, String b);
        public void postBanalceTransferAG(String appRefer, String f, String t, String b);
        public void postBanalceTransferOG(String appRefer, String f, String t, String b);
        public void postBanalceTransferCQ(String appRefer, String f, String t, String b);
        public void postBanalceTransferMW(String appRefer, String f, String t, String b);
        public void postBanalceTransferCP(String appRefer, String action, String from, String to, String fund);
        public void postPersonBalance(String appRefer,String action);
        public void postPersonBalanceCP(String appRefer,String action);
        public void postPersonBalanceKY(String appRefer,String action);
        public void postPersonBalanceHG(String appRefer,String action);
        public void postPersonBalanceVG(String appRefer,String action);
        public void postPersonBalanceLY(String appRefer,String action);
        public void postPersonBalanceMG(String appRefer,String action);
        public void postPersonBalanceAG(String appRefer,String action);
        public void postPersonBalanceOG(String appRefer,String action);
        public void postPersonBalanceCQ(String appRefer,String action);
        public void postPersonBalanceMW(String appRefer,String action);
        public void postPersonBalanceFG(String appRefer,String action);
        public void postBanalceTransferFG(String appRefer, String f, String t, String b);
        public void postPersonBalanceBBIN(String appRefer,String action);
        public void postBanalceTransferBBIN(String appRefer, String f, String t, String b);
    }
    public interface View extends IView<BalancePlatformContract.Presenter>,IMessageView,IProgressView
    {
        public void postBetRecordResult(BetRecordResult message);
        public void postPersonBalanceTYResult(KYBalanceResult personBalance);
        public void postPersonBalanceResult(KYBalanceResult personBalance);
        public void postPersonBalanceCPResult(KYBalanceResult personBalance);
        public void postPersonBalanceKYResult(KYBalanceResult personBalance);
        public void postPersonBalanceHGResult(KYBalanceResult personBalance);
        public void postPersonBalanceVGResult(KYBalanceResult personBalance);
        public void postPersonBalanceLYResult(KYBalanceResult personBalance);
        public void postPersonBalanceMGResult(KYBalanceResult personBalance);
        public void postPersonBalanceAGResult(KYBalanceResult personBalance);
        public void postPersonBalanceOGResult(KYBalanceResult personBalance);
        public void postPersonBalanceCQResult(KYBalanceResult personBalance);
        public void postPersonBalanceMWResult(KYBalanceResult personBalance);
        public void postPersonBalanceFGResult(KYBalanceResult personBalance);
        public void postPersonBalanceBBINResult(KYBalanceResult personBalance);
    }
}
