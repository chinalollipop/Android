package com.sunapp.bloc.personpage.balancetransfer;

import com.sunapp.bloc.base.IMessageView;
import com.sunapp.bloc.base.IPresenter;
import com.sunapp.bloc.base.IProgressView;
import com.sunapp.bloc.base.IView;
import com.sunapp.bloc.data.BetRecordResult;

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
        public void postBanalceTransferCP(String appRefer,String action, String from,String to, String fund);
    }
    public interface View extends IView<BalanceTransferContract.Presenter>,IMessageView,IProgressView
    {
        public void postBetRecordResult(BetRecordResult message);
    }
}
