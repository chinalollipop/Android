package com.hg3366.a3366.homepage.handicap.betapi.zhbetapi;

import com.hg3366.a3366.common.http.ResponseSubscriber;
import com.hg3366.a3366.common.http.request.AppTextMessageResponseList;
import com.hg3366.a3366.common.util.HGConstant;
import com.hg3366.a3366.common.util.RxHelper;
import com.hg3366.a3366.common.util.SubscriptionHelper;
import com.hg3366.a3366.data.BetZHResult;
import com.hg3366.a3366.data.GameAllZHBetsBKResult;
import com.hg3366.common.util.Check;

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
    public void start() {

    }

    @Override
    public void destroy() {

    }

}
