package com.venen.tian.depositpage;

import com.venen.tian.base.IMessageView;
import com.venen.tian.base.IPresenter;
import com.venen.tian.base.IProgressView;
import com.venen.tian.base.IView;
import com.venen.tian.data.DepositAliPayQCCodeResult;
import com.venen.tian.data.DepositBankCordListResult;
import com.venen.tian.data.DepositListResult;
import com.venen.tian.data.DepositThirdBankCardResult;
import com.venen.tian.data.DepositThirdQQPayResult;

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
