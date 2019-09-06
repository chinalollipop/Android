package com.cfcp.a01.ui.home;

import com.cfcp.a01.CFConstant;
import com.cfcp.a01.common.http.Client;
import com.cfcp.a01.common.http.ResponseSubscriber;
import com.cfcp.a01.common.http.RxHelper;
import com.cfcp.a01.common.http.SubscriptionHelper;
import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.common.http.request.AppTextMessageResponseList;
import com.cfcp.a01.common.http.util.Md5Utils;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.Timber;
import com.cfcp.a01.data.AllGamesResult;
import com.cfcp.a01.data.BannerResult;
import com.cfcp.a01.data.GameQueueMoneyResult;
import com.cfcp.a01.data.LogoutResult;
import com.cfcp.a01.data.NoticeResult;

import java.util.HashMap;
import java.util.Map;

import static com.cfcp.a01.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2017/4/20.
 */
public class HomePresenter implements HomeContract.Presenter {

    private IHomeApi api;
    private HomeContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public HomePresenter(IHomeApi api, HomeContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }


    @Override
    public void start() {

    }

    @Override
    public void destroy() {

        subscriptionHelper.unsubscribe();
    }

    @Override
    public void getBanner(String appRefer) {
        Map<String, String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet", "Notice");
        params.put("action", "GetBannerList");
        subscriptionHelper.add(RxHelper.addSugar(api.getBanner(params))
                .subscribe(new ResponseSubscriber<BannerResult>() {
                    @Override
                    public void success(BannerResult response) {
                        if (response.getErrno() == 0) {
                            view.getBannerResult(response);
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
    public void getNotice(String appRefer) {
        Map<String, String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet", "Notice");
        params.put("count", "20");
        params.put("action", "GetNoticeList");
        subscriptionHelper.add(RxHelper.addSugar(api.getNotice(params))
                .subscribe(new ResponseSubscriber<NoticeResult>() {
                    @Override
                    public void success(NoticeResult response) {
                        if (response.getErrno() == 0) {
                            view.getNoticeResult(response);
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
    public void getAllGames(String appRefer) {
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
                            view.getAllGamesResult(response);
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
    public void getAllGamesNew(String appRefer) {
        Map<String, String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet", "Game");
        params.put("platform", "cf");
        params.put("action", "GetAllGamesNew");
        subscriptionHelper.add(RxHelper.addSugar(api.getAllGames(params))
                .subscribe(new ResponseSubscriber<AllGamesResult>() {
                    @Override
                    public void success(AllGamesResult response) {
                        if (response.getErrno() == 0) {
                            view.getAllGamesNewResult(response);
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
    public void postLogout(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLogout(CFConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<LogoutResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<LogoutResult> response) {
                        if (response.isSuccess()) {
                            view.postLogoutResult(response.getDescribe());
                        } else {
                            Timber.d("快速登陆失败:%s", response);
                            view.showMessage(response.getDescribe());
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
    public void getJointLogin(String username) {
        //RetrofitUrlManager.getInstance().putDomain("CpUrl", CPClient.baseUrl());
       /* Map<String, String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet", "Credit");
        params.put("username", username);
        params.put("credit_token", Md5Utils.getMd5(Md5Utils.getMd5(Md5Utils.getMd5(username)+"_ssc")));
        params.put("action", "CreditLogin");
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));*/
        //String getUrl = Client.baseUrl()+"service?action=CreditLogin&packet=Credit&token="+ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
        //RetrofitUrlManager.getInstance().startAdvancedModel(getUrl);
//        RetrofitUrlManager.getInstance().putDomain("CpUrl", getUrl);
        subscriptionHelper.add(RxHelper.addSugar(api.getJointLogin("CreditLogin","Credit",ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN),username,Md5Utils.getMd5(Md5Utils.getMd5(Md5Utils.getMd5(username)+"_ssc")),CFConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<AllGamesResult>() {
                    @Override
                    public void success(AllGamesResult response) {
                        if (response.getErrno() == 0||response.getErrno() == 8002) {
                            view.getJointLoginResult(response.getError());
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
    public void getKaiYuanGame(String username) {
        Map<String, String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet", "ThirdGame");
        params.put("action", "KaiyuanGame");
        params.put("way", "index");
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getAllGames(params))
                .subscribe(new ResponseSubscriber<AllGamesResult>() {
                    @Override
                    public void success(AllGamesResult response) {
                        if (response.getErrno() == 0) {
                            view.getAllGamesResult(response);
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
    public void getAGGames(String username) {
        Map<String, String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet", "ThirdGame");
        params.put("gameType", "electronic");
        params.put("way", "login");
        params.put("gameid", "101");
        params.put("isTest", "0");
        params.put("action", "AgGame");
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getAGGames(params))
                .subscribe(new ResponseSubscriber<AllGamesResult>() {
                    @Override
                    public void success(AllGamesResult response) {
                        if (response.getErrno() == 0) {
                            view.getAllGamesResult(response);
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
    public void getAGVideoGames(String username) {
        Map<String, String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet", "ThirdGame");
        params.put("gameType", "immortal");
        params.put("way", "login");
        params.put("gameid", "8776");
        params.put("isTest", "0");
        params.put("action", "AgGame");
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getAGGames(params))
                .subscribe(new ResponseSubscriber<AllGamesResult>() {
                    @Override
                    public void success(AllGamesResult response) {
                        if (response.getErrno() == 0) {
                            view.getAllGamesResult(response);
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
    public void getAGFishGames(String username) {
        Map<String, String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet", "ThirdGame");
        params.put("gameType", "fishes");
        params.put("way", "login");
        params.put("gameid", "6");
        params.put("isTest", "0");
        params.put("action", "AgGame");
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getAGGames(params))
                .subscribe(new ResponseSubscriber<AllGamesResult>() {
                    @Override
                    public void success(AllGamesResult response) {
                        if (response.getErrno() == 0) {
                            view.getAllGamesResult(response);
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
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getPlayOutWithMoney(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<GameQueueMoneyResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<GameQueueMoneyResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getPlayOutWithMoneyResult(response.getData());
                        } /*else {
                            view.showMessage(response.getDescribe());
                        }*/
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

}

