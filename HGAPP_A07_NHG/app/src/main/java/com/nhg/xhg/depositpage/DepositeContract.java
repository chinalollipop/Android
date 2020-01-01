package com.nhg.xhg.depositpage;

import com.nhg.xhg.base.IMessageView;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.base.IProgressView;
import com.nhg.xhg.base.IView;
import com.nhg.xhg.data.DepositAliPayQCCodeResult;
import com.nhg.xhg.data.DepositBankCordListResult;
import com.nhg.xhg.data.DepositListResult;
import com.nhg.xhg.data.DepositThirdBankCardResult;
import com.nhg.xhg.data.DepositThirdQQPayResult;

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
