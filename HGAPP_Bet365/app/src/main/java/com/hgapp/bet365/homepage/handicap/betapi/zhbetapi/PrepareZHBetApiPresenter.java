package com.hgapp.bet365.homepage.handicap.betapi.zhbetapi;

import com.hgapp.bet365.common.http.ResponseSubscriber;
import com.hgapp.bet365.common.http.request.AppTextMessageResponse;
import com.hgapp.bet365.common.http.request.AppTextMessageResponseList;
import com.hgapp.bet365.common.util.HGConstant;
import com.hgapp.bet365.common.util.RxHelper;
import com.hgapp.bet365.common.util.SubscriptionHelper;
import com.hgapp.bet365.data.BetZHResult;
import com.hgapp.bet365.data.GameAllZHBetsBKResult;
import com.hgapp.bet365.data.PersonInformResult;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.Timber;

import java.util.Random;


public class PrepareZHBetApiPresenter implements PrepareZHBetApiContract.Presenter {
    private IPrepareZHBetApi api;
    private PrepareZHBetApiContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public PrepareZHBetApiPresenter(IPrepareZHBetApi api,PrepareZHBetApiContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }
    @Override
    public void postGameAllZHBetsBK(String appRefer, String game, String game_id) {
        subscriptionHelper.add(RxHelper.addSugar(api.postGameAllZHBetsBK(HGConstant.PRODUCT_PLATFORM,game,game_id))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<GameAllZHBetsBKResult> >() {
                    @Override
                    public void success(AppTextMessageResponseList<GameAllZHBetsBKResult>  response) {
                        if(!Check.isNull(response.getData())&&response.getStatus().equals("200")){
                            view.postGameAllZHBetsBKResult(response.getData().get(0));
                        }else{
                            view.postBetApiFailResult(response.getDescribe());
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
    public void postGameAllZHBetsFT(String appRefer, String game, String game_id) {
        subscriptionHelper.add(RxHelper.addSugar(api.postGameAllZHBetsFT(HGConstant.PRODUCT_PLATFORM,game,game_id))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<GameAllZHBetsBKResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<GameAllZHBetsBKResult> response) {
                        if(response.getStatus().equals("200")){
                            if(!Check.isNull(response.getData())&&response.getData().size()>0){

                                view.postGameAllZHBetsFTResult(response.getData().get(0));
                            }
                        }else{
                            view.postBetApiFailResult(response.getDescribe());
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
    public void postZHBetBK(String appRefer, String active, String teamcount, String gold, String wagerDatas) {
        Random random = new Random();
        String resultRandom="";
        for (int i=0;i<6;i++) {
            resultRandom += random.nextInt(10);
        }
        subscriptionHelper.add(RxHelper.addSugar(api.postZHBetBK(HGConstant.PRODUCT_PLATFORM,active,teamcount,gold,wagerDatas,resultRandom))
                .subscribe(new ResponseSubscriber<BetZHResult>() {
                    @Override
                    public void success(BetZHResult response) {
                        if(response.getStatus().equals("200")){
                            if(!Check.isNull(response.getData())&&response.getData().size()>0){
                                view.postZHBetFTResult(response);
                            }
                        }else{
                            view.postBetApiFailResult(response.getDescribe());
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
    public void postZHBetFT(String appRefer, String active, String teamcount, String gold, String wagerDatas) {
        Random random = new Random();
        String resultRandom="";
        for (int i=0;i<6;i++) {
            resultRandom += random.nextInt(10);
        }
        subscriptionHelper.add(RxHelper.addSugar(api.postZHBetFT(HGConstant.PRODUCT_PLATFORM,active,teamcount,gold,wagerDatas,resultRandom))
                .subscribe(new ResponseSubscriber<BetZHResult>() {
                    @Override
                    public void success(BetZHResult response) {
                        if(response.getStatus().equals("200")){
                            if(!Check.isNull(response.getData())&&response.getData().size()>0){
                                view.postZHBetFTResult(response);
                            }
                        }else{
                            view.postBetApiFailResult(response.getDescribe());
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
    public void getPersonInform(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonInform(HGConstant.PRODUCT_PLATFORM))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<PersonInformResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<PersonInformResult> response) {
                        if(response.isSuccess())
                        {
                            view.postPersonInformResult(response.getData());
                        }
                        else
                        {
                            view.showMessage(response.getDescribe());
                            Timber.d("快速登陆失败:%s",response);
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
