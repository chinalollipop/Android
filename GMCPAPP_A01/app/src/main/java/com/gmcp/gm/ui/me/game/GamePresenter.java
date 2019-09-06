package com.gmcp.gm.ui.me.game;

import com.gmcp.gm.CFConstant;
import com.gmcp.gm.common.http.ResponseSubscriber;
import com.gmcp.gm.common.http.RxHelper;
import com.gmcp.gm.common.http.SubscriptionHelper;
import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.data.GameQueueMoneyResult;

import java.util.HashMap;
import java.util.Map;

import static com.gmcp.gm.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2018/4/20.
 */
public class GamePresenter implements GameContract.Presenter {

    private IGameApi api;
    private GameContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public GamePresenter(IGameApi api, GameContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }


    @Override
    public void getLowerLevelReport(String action) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","ThirdGame");
        params.put("action",action);
        if("AgGame".equals(action)){
            params.put("way","getAgBlance");
        }else{
            params.put("way","queueMoney");

        }
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getLowerLevelReport(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<GameQueueMoneyResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<GameQueueMoneyResult> response) {
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
    public void getPlayOutWithMoney(final String action) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","ThirdGame");
        params.put("action",action);
        if("AgGame".equals(action)){
            params.put("way","playOutWithMoney");
        }else{
            params.put("way","platIn");
        }
        //params.put("way","playOutWithMoney");
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getLowerLevelReport(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<GameQueueMoneyResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<GameQueueMoneyResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            getLowerLevelReport(action);
                            view.getPlayOutWithMoneyResult(response.getData());
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

