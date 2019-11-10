package com.sands.corp.homepage.aglist.agchange;

import com.sands.corp.base.IMessageView;
import com.sands.corp.base.IPresenter;
import com.sands.corp.base.IProgressView;
import com.sands.corp.base.IView;
import com.sands.corp.data.BetRecordResult;
import com.sands.corp.data.PersonBalanceResult;

public interface AGPlatformContract {
    public interface Presenter extends IPresenter
    {
        public void postBanalceTransfer(String appRefer, String f, String t, String b);
        public void postMGBanalceTransfer(String appRefer, String f, String t, String b);
        public void postFGBanalceTransfer(String appRefer, String f, String t, String b);
        public void postFGPersonBalance(String appRefer, String action);
        public void postPersonBalance(String appRefer, String action);
        public void postMGPersonBalance(String appRefer, String action);
        public void postCQPersonBalance(String appRefer, String action);
        public void postMWPersonBalance(String appRefer, String action);
        public void postCQBanalceTransfer(String appRefer, String f, String t, String b);
        public void postMWBanalceTransfer(String appRefer, String f, String t, String b);
    }
    public interface View extends IView<AGPlatformContract.Presenter>,IMessageView,IProgressView
    {
        public void postBanalceTransferSuccess();
        public void postBetRecordResult(BetRecordResult message);
        public void postPersonBalanceResult(PersonBalanceResult personBalance);
        public void postMGPersonBalanceResult(PersonBalanceResult personBalance);
    }
}
