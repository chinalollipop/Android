package com.cfcp.a01.ui.home.bet;

import com.cfcp.a01.CFConstant;
import com.cfcp.a01.common.http.ResponseSubscriber;
import com.cfcp.a01.common.http.RxHelper;
import com.cfcp.a01.common.http.SubscriptionHelper;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.data.BetDataResult;
import com.cfcp.a01.data.BetGameSettingsForRefreshResult;

import java.util.LinkedHashMap;
import java.util.Map;

import static com.cfcp.a01.common.utils.Utils.getContext;

/**
 * Created by Daniel on 2018/4/20.
 */
public class BetFragmentPresenter implements BetFragmentContract.Presenter {

    private IBetFragmentApi api;
    private BetFragmentContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public BetFragmentPresenter(IBetFragmentApi api, BetFragmentContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void getGameSettingsForRefresh(int id, final boolean isRefresh) {
        Map<String, String> params = new LinkedHashMap<>();
        params.put("packet", "Game");
        params.put("action", "GetGameSettingsForRefresh");
        params.put("terminal_id", "2");
        params.put("lottery_id", String.valueOf(id));
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getGameSettingsForRefresh(params))
                .subscribe(new ResponseSubscriber<BetGameSettingsForRefreshResult>() {
                    @Override
                    public void success(BetGameSettingsForRefreshResult response) {
                        view.setGameSettingsForRefreshResult(response, isRefresh);
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
    public void getBet(int id, String betData) {
        Map<String, String> params = new LinkedHashMap<>();
        params.put("packet", "Game");
        params.put("action", "bet");
        params.put("terminal_id", "2");
        params.put("lottery_id", String.valueOf(id));
        params.put("betdata", betData);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getBet(params))
                .subscribe(new ResponseSubscriber<BetDataResult>() {
                    @Override
                    public void success(BetDataResult response) {
                        view.setBetResult(response);
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

