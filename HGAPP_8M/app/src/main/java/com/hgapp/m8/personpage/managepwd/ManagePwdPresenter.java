package com.hgapp.m8.personpage.managepwd;

import com.hgapp.m8.common.http.ResponseSubscriber;
import com.hgapp.m8.common.http.request.AppTextMessageResponse;
import com.hgapp.m8.common.util.HGConstant;
import com.hgapp.m8.common.util.RxHelper;
import com.hgapp.m8.common.util.SubscriptionHelper;

public class ManagePwdPresenter implements ManagePwdContract.Presenter {



    private IManagePwdApi iManagePwd;
    private ManagePwdContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public ManagePwdPresenter(IManagePwdApi iManagePwd, ManagePwdContract.View  view){
        this.view = view;
        this.iManagePwd = iManagePwd;
        this.view.setPresenter(this);
    }

    @Override
    public void getChangeLoginPwd(String appRefer, String action, String flag_action, String oldpassword, String password, String REpassword) {
        subscriptionHelper.add(RxHelper.addSugar(iManagePwd.postChangeLoginPwd(HGConstant.PRODUCT_PLATFORM,"1","1",oldpassword,password,REpassword))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<Object>>() {
                    @Override
                    public void success(AppTextMessageResponse<Object> response) {
                        if(response.isSuccess()){
                            view.onChangeLoginPwdResut(response.getDescribe());
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
    public void getChangeWithdrawPwd(String appRefer, String action, String flag_action, String pay_oldpassword, String pay_password, String pay_REpassword) {
        subscriptionHelper.add(RxHelper.addSugar(iManagePwd.postChangeWithDrawalPwd(HGConstant.PRODUCT_PLATFORM,"1","2",pay_oldpassword,pay_password,pay_REpassword))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<Object>>() {
                    @Override
                    public void success(AppTextMessageResponse<Object> response) {
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
