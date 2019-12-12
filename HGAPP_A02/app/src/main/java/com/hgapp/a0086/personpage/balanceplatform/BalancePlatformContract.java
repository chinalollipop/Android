package com.hgapp.a0086.personpage.balanceplatform;

import com.hgapp.a0086.base.IMessageView;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.base.IProgressView;
import com.hgapp.a0086.base.IView;
import com.hgapp.a0086.data.BetRecordResult;
import com.hgapp.a0086.data.KYBalanceResult;
import com.hgapp.a0086.data.PersonBalanceResult;

public interface BalancePlatformContract {
    public interface Presenter extends IPresenter
    {
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
        public void postPersonBalanceResult(PersonBalanceResult personBalance);
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
