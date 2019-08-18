package com.sunapp.bloc.personpage.bindingcard;

import com.sunapp.bloc.common.http.ResponseSubscriber;
import com.sunapp.bloc.common.http.request.AppTextMessageResponse;
import com.sunapp.bloc.common.util.HGConstant;
import com.sunapp.bloc.common.util.RxHelper;
import com.sunapp.bloc.common.util.SubscriptionHelper;
import com.sunapp.bloc.data.GetBankCardListResult;


public class BindingCardPresenter implements BindingCardContract.Presenter {


    private IBindingCardApi api;
    private BindingCardContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public BindingCardPresenter(IBindingCardApi api, BindingCardContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void postGetBankCardList(String appRefer, String action_type) {
        subscriptionHelper.add(RxHelper.addSugar(api.postGetBankCardList(HGConstant.PRODUCT_PLATFORM,action_type))
                .subscribe(new ResponseSubscriber<GetBankCardListResult>() {
                    @Override
                    public void success(GetBankCardListResult response) {
                        if(response.getStatus()==200){
                            view.postGetBankCardListResult(response);
                        }else{
                            view.showMessage(response.getDescribe());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
                            view.setError(0,0);
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void postBindingBankCard(String appRefer, String action_type, String bank_name, String bank_account, String bank_address, String pay_password, String pay_password2) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBindingBankCard(HGConstant.PRODUCT_PLATFORM,action_type,bank_name,bank_account,bank_address,pay_password,pay_password2))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<Object>>() {
                    @Override
                    public void success(AppTextMessageResponse<Object> response) {
                        if(response.isSuccess()){
                            view.postBindingBankCardResult(response.getDescribe());
                        }
                        view.showMessage(response.getDescribe());
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
                            view.setError(0,0);
                            view.showMessage(msg);
                        }
                    }
                }));

    }

    @Override
    public void start() {

    }

    @Override
    public void destroy() {

    }
}
