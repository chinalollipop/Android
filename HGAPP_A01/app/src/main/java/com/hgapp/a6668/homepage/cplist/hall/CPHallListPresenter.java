package com.hgapp.a6668.homepage.cplist.hall;

import com.hgapp.a6668.HGApplication;
import com.hgapp.a6668.common.http.Client;
import com.hgapp.a6668.common.http.ResponseSubscriber;
import com.hgapp.a6668.common.http.request.AppTextMessageResponse;
import com.hgapp.a6668.common.http.request.AppTextMessageResponseList;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.util.RxHelper;
import com.hgapp.a6668.common.util.SubscriptionHelper;
import com.hgapp.a6668.data.AGGameLoginResult;
import com.hgapp.a6668.data.AGLiveResult;
import com.hgapp.a6668.data.CPHallResult;
import com.hgapp.a6668.data.CPLeftInfoResult;
import com.hgapp.a6668.data.CheckAgLiveResult;
import com.hgapp.a6668.data.PersonBalanceResult;
import com.hgapp.a6668.launcher.MyHttpClient;
import com.hgapp.common.util.GameLog;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.net.URLDecoder;
import java.net.URLEncoder;
import java.text.SimpleDateFormat;
import java.util.Date;

import me.jessyan.retrofiturlmanager.RetrofitUrlManager;
import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.Response;
import retrofit2.Retrofit;


public class CPHallListPresenter implements CPHallListContract.Presenter {


    private ICPHallListApi api;
    private CPHallListContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public CPHallListPresenter(ICPHallListApi api, CPHallListContract.View  view){
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
    public void postLogin(String appRefer) {
        String yloginurl = ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_CP_INFORM);
        yloginurl.replace("http://mc.hg01455.com/","");
        subscriptionHelper.add(RxHelper.addSugar(api.getLoginCP(yloginurl))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<Object>>() {
                    @Override
                    public void success(AppTextMessageResponse<Object> response) {
                        if(response.isSuccess())
                        {
                            //view.postPersonBalanceResult(response.getData().get(0));
                        }
                        else
                        {
                            view.showMessage(response.getDescribe());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
                            view.setError(0,0);
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void postCPHallList(String appRefer) {
        //postLogin("");
        String date= System.currentTimeMillis()+"";
        GameLog.log("当前的时间戳 "+date.substring(0,10));
        String getUtl3 = ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.APP_CP_X_SESSION_TOKEN);
        GameLog.log("cp 的之前的token "+getUtl3);
       /* try {
            getUtl2 = URLDecoder.decode("gamessc/getAllNextIssue?_"+date+"&x-session-token="+getUtl3,"UTF-8");
        } catch (UnsupportedEncodingException e) {
            e.printStackTrace();
        }*/
        ACache.get(HGApplication.instance().getApplicationContext()).put(HGConstant.APP_CP_COOKIE_AVIABLE,"true");
        ACache.get(HGApplication.instance().getApplicationContext()).put("KKKKK","true");
        //GameLog.log("========token "+getUtl2);
        subscriptionHelper.add(RxHelper.addSugar(api.get("gamessc/getAllNextIssue?_"+date+"&x-session-token="+getUtl3))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CPHallResult>() {
                    @Override
                    public void success(CPHallResult response) {
                            view.postCPHallListResult(response);
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
                            view.setError(0,0);
                            view.showMessage(msg);
                        }
                    }
                }));

        subscriptionHelper.add(RxHelper.addSugar(api.postCPHallList("1",getUtl3))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CPLeftInfoResult>() {
                    @Override
                    public void success(CPLeftInfoResult response) {
                        GameLog.log(""+response.toString());
                        view.postCPLeftInfoResult(response);
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
                            view.setError(0,0);
                            view.showMessage(msg);
                        }
                    }
                }));
        /*subscriptionHelper.add(RxHelper.addSugar(api.postCPHallList("1",getUtl3))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CheckAgLiveResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<CheckAgLiveResult> response) {
                        if(response.isSuccess())
                        {
                            //view.postPersonBalanceResult(response.getData().get(0));
                        }
                        else
                        {
                            view.showMessage(response.getDescribe());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
                            view.setError(0,0);
                            view.showMessage(msg);
                        }
                    }
                }));*/
    }
}
