package com.sunapp.bloc.personpage.bindingcard;

import com.sunapp.bloc.base.IMessageView;
import com.sunapp.bloc.base.IPresenter;
import com.sunapp.bloc.base.IProgressView;
import com.sunapp.bloc.base.IView;
import com.sunapp.bloc.data.GetBankCardListResult;

public interface BindingCardContract {
    public interface Presenter extends IPresenter
    {
        public void postGetBankCardList(String appRefer, String action_type);
        public void postBindingBankCard(String appRefer, String action_type, String bank_name, String bank_account, String bank_address, String pay_password, String pay_password2);
    }
    public interface View extends IView<BindingCardContract.Presenter>,IMessageView,IProgressView
    {
        public void postGetBankCardListResult(GetBankCardListResult getBankCardListResult);
        public void postBindingBankCardResult(Object withdrawResult);
    }
}
