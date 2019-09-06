package com.gmcp.gm.ui.me.userlist.setprize;

import com.gmcp.gm.CFConstant;
import com.gmcp.gm.common.http.ResponseSubscriber;
import com.gmcp.gm.common.http.RxHelper;
import com.gmcp.gm.common.http.SubscriptionHelper;
import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.data.LoginResult;
import com.gmcp.gm.data.LowerSetDataResult;

import java.util.HashMap;
import java.util.Map;

import static com.gmcp.gm.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2018/4/20.
 */
public class SetPrizePresenter implements SetPrizeContract.Presenter {

    private ISetPrizeApi api;
    private SetPrizeContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public SetPrizePresenter(ISetPrizeApi api, SetPrizeContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }


    @Override
    public void getLowerLevelReport(String user_id) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Game");
        params.put("action","SetUserPrizeSet");
        params.put("user_id",user_id);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getLowerLevelReport(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<LowerSetDataResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<LowerSetDataResult> response) {
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
    public void getRealName(String user_id,String series_prize_group_json,String water,String qq) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Game");
        params.put("series_id","1");
        params.put("lottery_id","1");
        params.put("action","SetUserPrizeSet");
        params.put("user_id",user_id);
        params.put("series_prize_group_json",series_prize_group_json);
        params.put("agent_prize_set_quota","{}");
        params.put("kickback",water);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getRealName(params))
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

