package com.hgapp.betnew.common.http;

import android.content.Context;

import com.hgapp.common.util.GameLog;
import com.hgapp.common.util.NetworkUtils;
import com.hgapp.common.util.Timber;

import java.net.SocketTimeoutException;
import java.util.concurrent.TimeoutException;

import retrofit2.adapter.rxjava.HttpException;
import rx.Subscriber;

/**
 * Created by ak on 2017/7/29.
 * 统一管理网络异常、链接超时、服务器异常等等
 */

public abstract class ResponseSubscriber<T> extends Subscriber<T> {
    @Override
    public void onCompleted() {
        GameLog.log("onCompleted()");
    }
    private Context mContext;

    public ResponseSubscriber(Context context) {
        this.mContext = context;
    }

    public ResponseSubscriber(){}

    @Override
    public void onError(Throwable e) {
        Timber.e(e,"观察者观察到异常");
        if (!NetworkUtils.isConnected()) {
            fail("请检查您的网络");
        }

        GameLog.log("getMessage："+e.getMessage());
        GameLog.log("异常信息："+e.toString());

        if (e instanceof HttpException) {
            int code = ((HttpException) e).code();
            String errorMsg = ((HttpException) e).message();
            GameLog.log(errorMsg);
            if (code == 406 || code == 404) {//|| code == 404
                /*Client.cancelAllRequest();
                //intent.putExtra("msg", "登录信息已过期，请重新登录！");
                IUserManager userManager = UserManagerFactory.get();
                userManager.logout();
                EventBus.getDefault().post(new StartBrotherEvent(MainFragment.newInstance("distanceLogin", ""), SINGLETASK));
                EventBus.getDefault().post(new DistanceLoginEvent(406,"distanceLogin"));
                EventBus.getDefault().post(new LogoutEvent());*/
            } else {
                fail(errorMsg);
            }
            return;
        }

        //异常处理 连接超时
        if (e instanceof SocketTimeoutException || e instanceof TimeoutException ) {//|| e instanceof Exception
            fail("服务器开小差了，请稍后再试");
        }
    }

    @Override
    public void onNext(T t) {
        success(t);
    }

    @Override
    public void onStart() {

        GameLog.log("onStart()");
        if (!NetworkUtils.isConnected()) {
            if (!isUnsubscribed()) {
                unsubscribe();
            }
            onCompleted();
            fail("请检查您的网络");

        }
    }


    public abstract void success(T t);

    public abstract void fail(String msg);
}
