package com.hgapp.a0086.homepage.aglist.agchange;

import com.hgapp.a0086.base.IMessageView;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.base.IProgressView;
import com.hgapp.a0086.base.IView;
import com.hgapp.a0086.data.BetRecordResult;
import com.hgapp.a0086.data.PersonBalanceResult;

public interface AGPlatformContract {
    public interface Presenter extends IPresenter
    {
        public void postBanalceTransfer(String appRefer, String f, String t, String b);
        public void postMGBanalceTransfer(String appRefer, String f, String t, String b);
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
