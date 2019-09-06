package com.gmcp.gm.ui.home.login.fastregister;

import com.gmcp.gm.CFConstant;
import com.gmcp.gm.common.http.ResponseSubscriber;
import com.gmcp.gm.common.http.RxHelper;
import com.gmcp.gm.common.http.SubscriptionHelper;
import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.common.utils.Timber;
import com.gmcp.gm.data.LoginResult;

import java.util.HashMap;
import java.util.Map;


/**
 * Created by Daniel on 2017/4/20.
 */
public class RegisterPresenter implements RegisterContract.Presenter {

    private IRegisterApi api;
    private RegisterContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public RegisterPresenter(IRegisterApi api, RegisterContract.View view)
    {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }


    @Override
    public void postRegisterMember(String agent,String username, String password, String password2, String qq) {
        Map<String, String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet", "User");
        params.put("action", "AuthRegister");
        params.put("step", "2");
        params.put("code", agent);
        params.put("username", username);
        params.put("password", password);
        params.put("qq", qq);
        params.put("password_confirmation", password2);
        subscriptionHelper.add(RxHelper.addSugar(api.registerMember(params))//loginGet() login(appRefer,username,pwd) appRefer=13&type=FU&more=s
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<LoginResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<LoginResult> response) {
                        if(response.isSuccess())
                        {
                            //ACache.get(Utils.getContext()).put(CFConstant.USERNAME_LOGIN_DEMO, "false");
                            view.postRegisterMemberResult(response.getData());
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

