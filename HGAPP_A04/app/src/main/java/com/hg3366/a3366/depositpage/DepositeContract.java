package com.hg3366.a3366.depositpage;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.DepositAliPayQCCodeResult;
import com.hg3366.a3366.data.DepositBankCordListResult;
import com.hg3366.a3366.data.DepositListResult;
import com.hg3366.a3366.data.DepositThirdBankCardResult;
import com.hg3366.a3366.data.DepositThirdQQPayResult;

public interface DepositeContract {
    public interface Presenter extends IPresenter
    {
        public void postDepositList(String appRefer);
        public void postDepositBankCordList(String appRefer);
        public void postDepositAliPayQCCode(String appRefer,String bankid);
        public void postDepositWechatQCCode(String appRefer,String bankid);
        public void postDepositThirdUQCCode(String appRefer,String bankid);
        public void postDepositThirdBankCard(String appRefer);
        public void postDepositThirdWXPay(String appRefer);
        public void postDepositThirdAliPay(String appRefer);
        public void postDepositThirdQQPay(String appRefer);
    }
    public interface View extends IView<DepositeContract.Presenter>,IMessageView,IProgressView
    {
        public void postDepositListResult(DepositListResult message);
        public void postDepositBankCordListResult(DepositBankCordListResult message);
        public void postDepositAliPayQCCodeResult(DepositAliPayQCCodeResult message);
        public void postDepositThirdBankCardResult(DepositThirdBankCardResult message);
        public void postDepositThirdWXPayResult(DepositThirdQQPayResult message);
        public void postDepositThirdAliPayResult(DepositThirdQQPayResult message);
        public void postDepositThirdQQPayResult(DepositThirdQQPayResult message);

    }
}
