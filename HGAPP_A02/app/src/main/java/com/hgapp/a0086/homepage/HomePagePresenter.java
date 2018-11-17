package com.hgapp.a0086.homepage;

import com.hgapp.a0086.common.http.ResponseSubscriber;
import com.hgapp.a0086.common.http.request.AppTextMessageResponse;
import com.hgapp.a0086.common.http.request.AppTextMessageResponseList;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.common.util.RxHelper;
import com.hgapp.a0086.common.util.SubscriptionHelper;
import com.hgapp.a0086.data.AGCheckAcountResult;
import com.hgapp.a0086.data.BannerResult;
import com.hgapp.a0086.data.CPResult;
import com.hgapp.a0086.data.CheckAgLiveResult;
import com.hgapp.a0086.data.MaintainResult;
import com.hgapp.a0086.data.NoticeResult;
import com.hgapp.a0086.data.OnlineServiceResult;
import com.hgapp.a0086.data.QipaiResult;
import com.hgapp.a0086.data.ValidResult;
import com.hgapp.common.util.Check;


public class HomePagePresenter implements HomePageContract.Presenter {


    private IHomePageApi api;
    private HomePageContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public HomePagePresenter(IHomePageApi api, HomePageContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void postOnlineService(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postOnlineService(HGConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<OnlineServiceResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<OnlineServiceResult> response) {
                        if(response.isSuccess()){
                            view.postOnlineServiceResult(response.getData());
                        }else{
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
    public void postBanner(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanner(HGConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<BannerResult>() {
                    @Override
                    public void success(BannerResult response) {
                        if(response.getStatus()==200){
                            view.postBannerResult(response);
                        }else{
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
    public void postNotice(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postNotice(HGConstant.PRODUCT_PLATFORM,"1"))
                .subscribe(new ResponseSubscriber<NoticeResult>() {
                    @Override
                    public void success(NoticeResult response) {
                        if(response.getStatus()==200){
                            view.postNoticeResult(response);
                        }else{
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
    public void postNoticeList(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postNotice(HGConstant.PRODUCT_PLATFORM,""))
                .subscribe(new ResponseSubscriber<NoticeResult>() {
                    @Override
                    public void success(NoticeResult response) {
                        if(response.getStatus()==200){
                            view.postNoticeListResult(response);
                        }else{
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
    public void postAGLiveCheckRegister(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postAGLiveCheckRegister(HGConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CheckAgLiveResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<CheckAgLiveResult> response) {

                        if(response.isSuccess()){
                            view.postAGLiveCheckRegisterResult(response.getData());
                        }else{
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
    public void postAGGameRegisterAccount(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postAGGameRegisterAccount(HGConstant.PRODUCT_PLATFORM,"cga"))
                .subscribe(new ResponseSubscriber<AGCheckAcountResult>() {
                    @Override
                    public void success(AGCheckAcountResult response) {

                        if("200".equals(response.getStatus())){
                            view.postAGGameRegisterAccountResult(response);
                        }else{
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
    public void postQipai(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postQiPai(HGConstant.PRODUCT_PLATFORM,"cm"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<QipaiResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<QipaiResult> response) {
                        if(response.isSuccess()){
                            if(!Check.isNull(response.getData())){
                                view.postQipaiResult(response.getData());
                            }
                        }else{
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
    public void postHGQipai(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postHGQiPai(HGConstant.PRODUCT_PLATFORM,"cm"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<QipaiResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<QipaiResult> response) {
                        if(response.isSuccess()){
                            if(!Check.isNull(response.getData())){
                                view.postHGQipaiResult(response.getData());
                            }
                        }else{
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
    public void postCP() {
        subscriptionHelper.add(RxHelper.addSugar(api.postCP(HGConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<CPResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<CPResult> response) {

                        if(!Check.isNull(response)&&response.getData().size()>0){
                            view.postCPResult(response.getData().get(0));
                        }else{
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
    public void postValidGift(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postValidGift(HGConstant.PRODUCT_PLATFORM,action))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<ValidResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<ValidResult> response) {
                        //view.postDownAppGiftResult("38");
                        if(response.isSuccess()){
                            view.postValidGiftResult(response.getData().get(0));
                        }else{
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
    public void postMaintain() {
        subscriptionHelper.add(RxHelper.addSugar(api.postMaintain(HGConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<MaintainResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<MaintainResult> response) {
                        //view.postDownAppGiftResult("38");
                        if(response.isSuccess()){
                            view.postMaintainResult(response.getData());
                        }else{
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
    public void start() {

    }

    @Override
    public void destroy() {

    }
}
