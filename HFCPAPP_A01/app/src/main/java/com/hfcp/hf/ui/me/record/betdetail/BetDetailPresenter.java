package com.hfcp.hf.ui.me.record.betdetail;

import com.hfcp.hf.CFConstant;
import com.hfcp.hf.common.http.ResponseSubscriber;
import com.hfcp.hf.common.http.RxHelper;
import com.hfcp.hf.common.http.SubscriptionHelper;
import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.common.utils.ACache;
import com.hfcp.hf.data.BetDetailResult;

import java.util.HashMap;
import java.util.Map;

import static com.hfcp.hf.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2018/4/20.
 */
public class BetDetailPresenter implements BetDetailContract.Presenter {

    private IBetDetailApi api;
    private BetDetailContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public BetDetailPresenter(IBetDetailApi api, BetDetailContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }


    @Override
    public void getProjectDetail(String id) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Game");
        params.put("action","GetProjectDetail");
        params.put("id",id);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getProjectDetail(params))//CFConstant.PRODUCT_PLATFORM, "User", "Login", username, password, "1"
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BetDetailResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BetDetailResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getProjectDetailResult(response.getData());
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
    public void getProjectDrop(String id) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Game");
        params.put("action","DropProject");
        params.put("id",id);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getProjectDetail(params))//CFConstant.PRODUCT_PLATFORM, "User", "Login", username, password, "1"
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BetDetailResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BetDetailResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getProjectDropResult(response.getData());
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

