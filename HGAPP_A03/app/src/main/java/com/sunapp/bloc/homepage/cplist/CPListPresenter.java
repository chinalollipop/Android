package com.sunapp.bloc.homepage.cplist;

import com.sunapp.bloc.HGApplication;
import com.sunapp.bloc.common.http.ResponseSubscriber;
import com.sunapp.bloc.common.util.ACache;
import com.sunapp.bloc.common.util.HGConstant;
import com.sunapp.bloc.common.util.RxHelper;
import com.sunapp.bloc.common.util.SubscriptionHelper;
import com.sunapp.bloc.data.CPNoteResult;
import com.sunapp.common.util.GameLog;


public class CPListPresenter implements CPListContract.Presenter {


    private ICPListApi api;
    private CPListContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public CPListPresenter(ICPListApi api, CPListContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }


    @Override
    public void start() {

    }

    @Override
    public void destroy() {

    }

    @Override
    public void postCPLogin(final String path) {
        GameLog.log("转移字符 " + path);//http://api.tianapi.com/social/?key=980945417cdcd426d6a391e5993c9cc8&num=10
        // RetrofitUrlManager.getInstance().putDomain("CpUrl", "http://mc.hg01455.com/");
        //ACache.get(HGApplication.instance().getApplicationContext()).put(HGConstant.APP_CP_COOKIE,"1");
        subscriptionHelper.add(RxHelper.addSugar(api.postCPLogin(path))
                .subscribe(new ResponseSubscriber<Object>() {
                    @Override
                    public void success(Object response) {
                        GameLog.log("联合登录的日志信息是 "+response);
                        ACache.get(HGApplication.instance().getApplicationContext()).put(HGConstant.APP_CP_COOKIE_AVIABLE,"true");
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
                            view.setError(0, 0);
                            view.showMessage(msg);
                        }
                    }
                }));


        /*RetrofitUrlManager.getInstance().putDomain("CpUrl", "http://api.tianapi.com/");
        subscriptionHelper.add(RxHelper.addSugar(api.postCPLogin("http://api.tianapi.com/social/?key=980945417cdcd426d6a391e5993c9cc8&num=10"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<Object>>() {
                    @Override
                    public void success(AppTextMessageResponse<Object> response) {

                        if (response.isSuccess()) {

                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
                            view.setError(0, 0);
                            view.showMessage(msg);
                        }
                    }
                }));*/
        /*RetrofitUrlManager.getInstance().putDomain("CpUrl", "https://api.hces888.com/");
        subscriptionHelper.add(RxHelper.addSugar(api.postCPLogin("3","","1","0","0","","zh-CN"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<Object>>() {
                    @Override
                    public void success(AppTextMessageResponse<Object> response) {

                        if (response.isSuccess()) {

                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
                            view.setError(0, 0);
                            view.showMessage(msg);
                        }
                    }
                }));*/

    }

    @Override
    public void postCPNote(String token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postCPNote("home/getnote?x-session-token=" + token))
                .subscribe(new ResponseSubscriber<CPNoteResult>() {
                    @Override
                    public void success(CPNoteResult response) {
                        view.postCPNoteResult(response);
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
                            view.setError(0, 0);
                            view.showMessage(msg);
                        }
                    }
                }));
        /*subscriptionHelper.add(RxHelper.addSugar(api.postCPInit("gamessc/init",token))
                .subscribe(new ResponseSubscriber<CPInitResult>() {
                    @Override
                    public void success(CPInitResult response) {
                        GameLog.log("联合登录的CPInitResult日志信息是 "+response.getToken());
                        ACache.get(HGApplication.instance().getApplicationContext()).put(HGConstant.APP_CP_X_SESSION_TOKEN,response.getToken());
                        postCPNote();
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
                            view.setError(0, 0);
                            view.showMessage(msg);
                        }
                    }
                }));*/
    }

}
