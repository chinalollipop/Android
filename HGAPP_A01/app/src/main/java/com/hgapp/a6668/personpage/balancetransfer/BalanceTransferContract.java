package com.hgapp.a6668.personpage.balancetransfer;

import com.hgapp.a6668.base.IMessageView;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.base.IProgressView;
import com.hgapp.a6668.base.IView;
import com.hgapp.a6668.data.BetRecordResult;

public interface BalanceTransferContract {
    public interface Presenter extends IPresenter
    {
        public void postBanalceTransfer(String appRefer, String f, String t,String b);
        public void postBanalceTransferKY(String appRefer, String f, String t, String b);
        public void postBanalceTransferHG(String appRefer, String f, String t, String b);
        public void postBanalceTransferVG(String appRefer, String f, String t, String b);
        public void postBanalceTransferLY(String appRefer, String f, String t, String b);
        public void postBanalceTransferMG(String appRefer, String f, String t, String b);
        public void postBanalceTransferAG(String appRefer, String f, String t, String b);
        public void postBanalceTransferOG(String appRefer, String f, String t, String b);
        public void postBanalceTransferCQ(String appRefer, String f, String t, String b);
        public void postBanalceTransferMW(String appRefer, String f, String t, String b);
        public void postBanalceTransferFG(String appRefer, String f, String t, String b);
        public void postBanalceTransferBBIN(String appRefer, String f, String t, String b);
        public void postBanalceTransferCP(String appRefer,String action, String from,String to, String fund);
    }
    public interface View extends IView<BalanceTransferContract.Presenter>,IMessageView,IProgressView
    {
        public void postBetRecordResult(BetRecordResult message);
    }
}
