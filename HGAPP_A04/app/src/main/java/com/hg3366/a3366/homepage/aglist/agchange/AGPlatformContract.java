package com.hg3366.a3366.homepage.aglist.agchange;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.BetRecordResult;
import com.hg3366.a3366.data.PersonBalanceResult;

public interface AGPlatformContract {
    public interface Presenter extends IPresenter
    {
        public void postBanalceTransfer(String appRefer, String f, String t, String b);
        public void postMGBanalceTransfer(String appRefer, String f, String t, String b);

        public void postPersonBalance(String appRefer, String action);
        public void postMGPersonBalance(String appRefer, String action);
    }
    public interface View extends IView<AGPlatformContract.Presenter>,IMessageView,IProgressView
    {
        public void postBanalceTransferSuccess();
        public void postBetRecordResult(BetRecordResult message);
        public void postPersonBalanceResult(PersonBalanceResult personBalance);
        public void postMGPersonBalanceResult(PersonBalanceResult personBalance);
    }
}
