package com.hfcp.hf.ui.me.register;

import com.hfcp.hf.CFConstant;
import com.hfcp.hf.common.http.ResponseSubscriber;
import com.hfcp.hf.common.http.RxHelper;
import com.hfcp.hf.common.http.SubscriptionHelper;
import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.common.utils.ACache;
import com.hfcp.hf.data.RegisterMeResult;

import java.util.HashMap;
import java.util.Map;

import static com.hfcp.hf.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2018/4/20.
 */
public class RegisterMePresenter implements RegisterMeContract.Presenter {

    private IRegisterMeApi api;
    private RegisterMeContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public RegisterMePresenter(IRegisterMeApi api, RegisterMeContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }


    @Override
    public void getFundGroup() {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","User");
        params.put("action","UserUser");
        params.put("way","accurateCreate");
        params.put("token",ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getFundGroup(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<RegisterMeResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<RegisterMeResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getFundGroupResult(response.getData());
                        } else {
                            view.showMessage(response.getDescribe());
                        }
                        //view.postLoginResult(response.getData());
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void getRegisterFundGroup(String is_agent,String prize_group_id,String prize_group_type, String nickname, String username, String password,String series_prize_group_json) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","User");
        params.put("action","UserUser");
        params.put("way","accurateCreate");
        params.put("is_agent",is_agent);
        params.put("prize_group_id","");
        params.put("agent_prize_set_quota","{}");
        params.put("fb_single","0.0");
        params.put("fb_all","0.0");
        params.put("lottery_id","");
        params.put("prize_group_type",prize_group_type);
        params.put("nickname",nickname);
        params.put("username",username);
        params.put("password",password);
        params.put("series_prize_group_json",series_prize_group_json);
        params.put("token",ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getFundGroup(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<RegisterMeResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<RegisterMeResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getRegisterFundGroupResult();
                        } else {
                            view.showMessage(response.getDescribe());
                        }
                        //view.postLoginResult(response.getData());
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
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

