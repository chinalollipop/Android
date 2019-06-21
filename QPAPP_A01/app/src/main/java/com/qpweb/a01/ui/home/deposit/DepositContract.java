package com.qpweb.a01.ui.home.deposit;

import com.qpweb.a01.base.IMessageView;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.base.IView;
import com.qpweb.a01.data.DepositAliPayQCCodeResult;
import com.qpweb.a01.data.DepositBankCordListResult;
import com.qpweb.a01.data.DepositListResult;
import com.qpweb.a01.data.DepositThirdBankCardResult;
import com.qpweb.a01.data.DepositThirdQQPayResult;
import com.qpweb.a01.data.LoginResult;

import java.util.List;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface DepositContract {

    public interface Presenter extends IPresenter {

        public void postLogin(String appRefer, String username, String password);
        public void postDepositBankCordList(String appRefer);
        public void postDepositAliPayQCCode(String appRefer,String bankid);
        public void postDepositWechatQCCode(String appRefer,String bankid);
        public void postDepositThirdBankCard(String appRefer);
        public void postDepositThirdWXPay(String appRefer);
        public void postDepositThirdAliPay(String appRefer);
        public void postDepositThirdQQPay(String appRefer);
        public void postDepositCompanyPaySubimt(String appRefer,String payid,String v_Name,String InType,String v_amount,String cn_date,String memo,String IntoBank);
        public void postDepositAliPayQCPaySubimt(String appRefer, String payid,  String v_amount, String cn_date, String memo,String bank_user);
    }

    public interface View extends IView<Presenter>, IMessageView {

        public void postLoginResult(List<DepositListResult> depositListResult);
        public void postDepositBankCordListResult(DepositBankCordListResult message);
        public void postDepositAliPayQCCodeResult(DepositAliPayQCCodeResult message);
        public void postDepositThirdBankCardResult(DepositThirdBankCardResult message);
        public void postDepositThirdWXPayResult(DepositThirdQQPayResult message);
        public void postDepositThirdAliPayResult(DepositThirdQQPayResult message);
        public void postDepositThirdQQPayResult(DepositThirdQQPayResult message);
    }
}
