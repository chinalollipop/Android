package com.cfcp.a01.ui.home;

import com.cfcp.a01.data.BannerResult;
import com.cfcp.a01.data.LogoutResult;
import com.cfcp.a01.data.NoticeResult;
import com.cfcp.a01.data.WinNewsResult;
import com.cfcp.a01.http.ResponseSubscriber;
import com.cfcp.a01.http.RxHelper;
import com.cfcp.a01.http.SubscriptionHelper;
import com.cfcp.a01.http.request.AppTextMessageResponseList;
import com.cfcp.a01.utils.QPConstant;
import com.cfcp.a01.utils.Timber;


/**
 * Created by Daniel on 2017/4/20.
 */
public class HomePresenter implements HomeContract.Presenter {

    private IHomeApi api;
    private HomeContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public HomePresenter(IHomeApi api, HomeContract.View view)
    {
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
        view = null;
        api = null;
    }

    @Override
    public void postBanner(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanner(QPConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<BannerResult>() {
                    @Override
                    public void success(BannerResult response) {
                        if(response.getStatus()==200)
                        {
                            view.postBannerResult(response);
                        }
                        else
                        {
                            Timber.d("快速登陆失败:%s",response);
                            view.showMessage(response.getDescribe());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void postNotice(String appRefer, String type) {
        subscriptionHelper.add(RxHelper.addSugar(api.postNotice(QPConstant.PRODUCT_PLATFORM,type))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<NoticeResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<NoticeResult> response) {
                        if(response.isSuccess())
                        {
                            view.postNoticeResult(response.getData());
                        }
                        else
                        {
                            Timber.d("快速登陆失败:%s",response);
                            view.showMessage(response.getDescribe());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void postWinNews(String appRefer, String news) {
        subscriptionHelper.add(RxHelper.addSugar(api.postWinNews(QPConstant.PRODUCT_PLATFORM,news))
                .subscribe(new ResponseSubscriber<WinNewsResult>() {
                    @Override
                    public void success(WinNewsResult response) {
                        if(response.getStatus()==200)
                        {
                            view.postWinNewsResult(response);
                        }
                        else
                        {
                            Timber.d("快速登陆失败:%s",response);
                            view.showMessage(response.getMessage());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void postLogout(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLogout(QPConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<LogoutResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<LogoutResult> response) {
                        if(response.isSuccess())
                        {
                            view.postLogoutResult(response.getDescribe() );
                        }
                        else
                        {
                            Timber.d("快速登陆失败:%s",response);
                            view.showMessage(response.getDescribe());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
                            view.showMessage(msg);
                        }
                    }
                }));
    }
}

