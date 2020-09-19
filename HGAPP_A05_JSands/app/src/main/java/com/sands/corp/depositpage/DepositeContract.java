package com.sands.corp.depositpage;

import com.sands.corp.base.IMessageView;
import com.sands.corp.base.IPresenter;
import com.sands.corp.base.IProgressView;
import com.sands.corp.base.IView;
import com.sands.corp.data.DepositAliPayQCCodeResult;
import com.sands.corp.data.DepositBankCordListResult;
import com.sands.corp.data.DepositListResult;
import com.sands.corp.data.DepositThirdBankCardResult;
import com.sands.corp.data.DepositThirdQQPayResult;

public interface DepositeContract {
    public interface Presenter extends IPresenter
    {
        public void postDepositList(String appRefer);
        public void postDepositBankCordList(String appRefer);
        public void postDepositAliPayQCCode(String appRefer,String bankid);
        public void postDepositWechatQCCode(String appRefer,String bankid);
        public void postDepositThirdUQCCode(String appRefer,String bankid);
        public void postDepositThirdUSDTCCode(String appRefer,String bankid);
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
        public void postDepositUSDTPayCCodeResult(DepositAliPayQCCodeResult message);
        public void postDepositThirdBankCardResult(DepositThirdBankCardResult message);
        public void postDepositThirdWXPayResult(DepositThirdQQPayResult message);
        public void postDepositThirdAliPayResult(DepositThirdQQPayResult message);
        public void postDepositThirdQQPayResult(DepositThirdQQPayResult message);

    }
}
