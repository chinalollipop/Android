package com.gmcp.gm.ui.me.link;

import com.gmcp.gm.CFConstant;
import com.gmcp.gm.common.http.ResponseSubscriber;
import com.gmcp.gm.common.http.RxHelper;
import com.gmcp.gm.common.http.SubscriptionHelper;
import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.data.RegisterLinkListResult;
import com.gmcp.gm.data.RegisterMeResult;

import java.util.HashMap;
import java.util.Map;

import static com.gmcp.gm.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2018/4/20.
 */
public class RegisterLinkPresenter implements RegisterLinkContract.Presenter {

    private IRegisterLinkApi api;
    private RegisterLinkContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public RegisterLinkPresenter(IRegisterLinkApi api, RegisterLinkContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }


    @Override
    public void getFundGroup() {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","User");
        params.put("action","UserRegisterLink");
        params.put("way","create");
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
    public void getFundList() {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","User");
        params.put("action","UserRegisterLink");
        params.put("way","index");
        params.put("pagesize","1000");
        params.put("token",ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getFundList(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<RegisterLinkListResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<RegisterLinkListResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getFundListResult(response.getData());
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
    public void getFundDelete(String id) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","User");
        params.put("action","UserRegisterLink");
        params.put("way","closeLink");
        params.put("id",id);
        params.put("token",ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getFundGroup(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<RegisterMeResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<RegisterMeResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getFundDeleteResult();
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
    public void getRegisterFundGroup(String is_agent,String prize_group_id,String prize_group_type,String channel, String agent_qqs, String valid_days,String series_prize_group_json) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","User");
        params.put("action","UserRegisterLink");
        params.put("way","create");
        params.put("is_agent",is_agent);
        params.put("prize_group_id",prize_group_id);
        params.put("agent_prize_set_quota","{}");
        params.put("fb_single","0.0");
        params.put("fb_all","0.0");
        params.put("lottery_id","");
        params.put("prize_group_type",prize_group_type);
        params.put("channel",channel);
        params.put("agent_qqs[]",agent_qqs);
        params.put("valid_days",valid_days);
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

