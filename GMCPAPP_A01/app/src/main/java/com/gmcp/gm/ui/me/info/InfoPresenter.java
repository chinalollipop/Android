package com.gmcp.gm.ui.me.info;

import com.gmcp.gm.CFConstant;
import com.gmcp.gm.common.http.ResponseSubscriber;
import com.gmcp.gm.common.http.RxHelper;
import com.gmcp.gm.common.http.SubscriptionHelper;
import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.data.LoginResult;
import com.gmcp.gm.data.LowerInfoDataResult;

import java.util.HashMap;
import java.util.Map;

import static com.gmcp.gm.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2018/4/20.
 */
public class InfoPresenter implements InfoContract.Presenter {

    private IInfoApi api;
    private InfoContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public InfoPresenter(IInfoApi api, InfoContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }


    @Override
    public void getLowerLevelReport(String user_id) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","User");
        params.put("action","GetCurrentUserInfo");
        params.put("user_id",user_id);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getLowerLevelReport(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<LowerInfoDataResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<LowerInfoDataResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getLowerLevelReportResult(response.getData());
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
    public void getRealName(String mobile,String name,String email,String qq) {
        subscriptionHelper.add(RxHelper.addSugar(api.getRealName(CFConstant.PRODUCT_PLATFORM,"User","SetTrueName",name,ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN)))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<LoginResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<LoginResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getRealNameResult(response.getData());
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

