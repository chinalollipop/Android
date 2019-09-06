package com.gmcp.gm.ui.home.bet;

import com.gmcp.gm.CFConstant;
import com.gmcp.gm.common.http.ResponseSubscriber;
import com.gmcp.gm.common.http.RxHelper;
import com.gmcp.gm.common.http.SubscriptionHelper;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.data.AllGamesResult;
import com.gmcp.gm.data.BetDataResult;
import com.gmcp.gm.data.BetGameSettingsForRefreshResult;
import com.gmcp.gm.data.GamesTipsResult;

import java.util.HashMap;
import java.util.LinkedHashMap;
import java.util.Map;

import static com.gmcp.gm.common.utils.Utils.getContext;

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
                        try {
                            view.setGameSettingsForRefreshResult(response, isRefresh);
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
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
    public void getAllGames() {
        Map<String, String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet", "Game");
        params.put("platform", "cf");
        params.put("action", "GetAllGames");
        subscriptionHelper.add(RxHelper.addSugar(api.getAllGames(params))
                .subscribe(new ResponseSubscriber<AllGamesResult>() {
                    @Override
                    public void success(AllGamesResult response) {
                        if (response.getErrno() == 0) {
                            view.setAllGamesResult(response);
                        } else {
                            view.showMessage(response.getError());
                        }
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
    public void getGamesTips() {
        Map<String, String> params = new HashMap<>();
        params.put("packet", "Notice");
        params.put("action", "GetNoticePrize");
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getGamesTips(params))
                .subscribe(new ResponseSubscriber<GamesTipsResult>() {
                    @Override
                    public void success(GamesTipsResult response) {
                        if (response.getErrno() == 0) {
                            view.setGamesTipsResult(response);
                        } else {
                            view.showMessage(response.getError());
                        }
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

