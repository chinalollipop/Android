package com.hgapp.a6668.demo;

import com.hgapp.a6668.HGApplication;
import com.hgapp.a6668.common.http.ResponseSubscriber;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.util.RxHelper;
import com.hgapp.a6668.common.util.SubscriptionHelper;
import com.hgapp.a6668.data.CPInitResult;
import com.hgapp.a6668.data.CPNoteResult;
import com.hgapp.common.util.GameLog;


public class CPDemoPresenter implements CPDemoContract.Presenter {


    private ICPDemoApi api;
    private CPDemoContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public CPDemoPresenter(ICPDemoApi api, CPDemoContract.View  view){
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
                        //postCPInit();
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
                            view.setError(0, 0);
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void postCPInit() {
        String[] cptoken = ACache.get(HGApplication.instance().getApplicationContext()).getAsString("COOKIE_token").split("; ");
        ACache.get(HGApplication.instance().getApplicationContext()).put(HGConstant.APP_CP_X_SESSION_TOKEN,cptoken[0].replace("token=",""));
        GameLog.log("彩票的token "+cptoken[0].replace("token=",""));
        subscriptionHelper.add(RxHelper.addSugar(api.postCPInit("gamessc/init",cptoken[0].replace("token=","")))
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
                }));
    }

    private void postCPNote() {
        String token = ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.APP_CP_X_SESSION_TOKEN);
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
    }
}
