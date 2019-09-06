package com.gmcp.gm.common.http;


import com.gmcp.gm.CFConstant;
import com.gmcp.gm.common.utils.NetworkUtils;
import com.gmcp.gm.common.utils.Timber;

import java.util.concurrent.TimeUnit;

import rx.Observable;
import rx.android.schedulers.AndroidSchedulers;
import rx.functions.Action0;
import rx.schedulers.Schedulers;


/**
 * Created by daniel on 2017/5/19.
 */

public class RxHelper {

    private RxHelper(){}
    @Override
    protected Object clone()
    {
        throw new RuntimeException("i cannot be cloned,as you know");
    }
    /**
     * 在这里给{@code Observable}统一加点糖-例如防止重复执行、子线程订阅、主线程通知、没有网络等等
     * @param observable
     * @return
     */
    public static <T> Observable<T> addSugar(final Observable<T> observable)
    {
        return observable
                .throttleFirst(CFConstant.throttleWindowTime, TimeUnit.SECONDS)
                /*.subscribe(new Consumer<observable>() {
                    @Override
                    public void accept(@NonNull observable userBean) throws Exception {
                    }
                })*/
                .doOnSubscribe(new Action0() {
                    @Override
                    public void call() {
                        Timber.d("doOnSubscribe");
                        if(!NetworkUtils.isConnected())
                        {
                            throw new RuntimeException("网络异常。。。。");
                        }
                    }
                })
                .subscribeOn(Schedulers.io())
                .observeOn(AndroidSchedulers.mainThread());
    }
}
