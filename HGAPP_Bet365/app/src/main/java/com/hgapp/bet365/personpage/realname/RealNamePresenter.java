package com.hgapp.bet365.personpage.realname;

import com.hgapp.bet365.common.http.ResponseSubscriber;
import com.hgapp.bet365.common.http.request.AppTextMessageResponse;
import com.hgapp.bet365.common.http.request.AppTextMessageResponseList;
import com.hgapp.bet365.common.util.HGConstant;
import com.hgapp.bet365.common.util.RxHelper;
import com.hgapp.bet365.common.util.SubscriptionHelper;
import com.hgapp.bet365.data.LoginResult;
import com.hgapp.common.util.Timber;

/**
 * Created by Daniel on 2017/4/20.
 */
public class RealNamePresenter implements RealNameContract.Presenter {

    private IRealNameApi api;
    private RealNameContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public RealNamePresenter(IRealNameApi api, RealNameContract.View view)
    {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void postUpdataRealName(String appRefer, String realname, String phone, String wechat, String birthday) {
        subscriptionHelper.add(RxHelper.addSugar(api.postUpdataRealName(HGConstant.PRODUCT_PLATFORM,realname,phone,wechat,birthday))//loginGet() login(appRefer,username,pwd) appRefer=13&type=FU&more=s
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<LoginResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<LoginResult> response) {
                        if(response.isSuccess())
                        {
                            view.postRegisterMemberResult(response.getDescribe());
                        }
                        else
                        {
                            Timber.d("快速登陆失败:%s",response);
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
    public void start() {

    }

    @Override
    public void destroy() {

        subscriptionHelper.unsubscribe();
        view = null;
        api = null;
    }

}

