package com.sands.corp.homepage.sportslist.bet;

import com.sands.corp.common.http.ResponseSubscriber;
import com.sands.corp.common.http.request.AppTextMessageResponse;
import com.sands.corp.common.util.HGConstant;
import com.sands.corp.common.util.RxHelper;
import com.sands.corp.common.util.SubscriptionHelper;
import com.sands.corp.data.BetResult;
import com.sands.corp.data.SportsPlayMethodResult;
import com.sands.common.util.GameLog;

import java.util.Random;


public class BetPresenter implements BetContract.Presenter {


    private IBetApi api;
    private BetContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public BetPresenter(IBetApi api, BetContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }


    @Override
    public void postSportsPlayMethod(String appRefer, String type, String more, String gid) {
        subscriptionHelper.add(RxHelper.addSugar(api.postSprotsPlayMethod(HGConstant.PRODUCT_PLATFORM,type,more,gid))
                .subscribe(new ResponseSubscriber<SportsPlayMethodResult>() {
                    @Override
                    public void success(SportsPlayMethodResult sportsPlayMethodResult) {
                        if("200".equals(sportsPlayMethodResult.getStatus())){
                            if(sportsPlayMethodResult.getData().size()==0){
                                view.showMessage("暂时无数据，请稍后再试！");
                            }else{
                                view.postSportsPlayMethodResult(sportsPlayMethodResult);
                            }
                            //view.postOnlineServiceResult(response.getData());

                        }else{
                            view.showMessage(sportsPlayMethodResult.getDescribe());
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
    public void postSportsPlayRBMethod(String appRefer, String type, String more, String gid) {
        subscriptionHelper.add(RxHelper.addSugar(api.postSprotsPlayRBMethod(HGConstant.PRODUCT_PLATFORM,type,more,gid))
                .subscribe(new ResponseSubscriber<String>() {
                    @Override
                    public void success(String sportsPlayMethodResult) {
                        GameLog.log("滚球的数据格式是："+sportsPlayMethodResult);
                        /*if("200".equals(sportsPlayMethodResult.getStatus())){
                            //view.postOnlineServiceResult(response.getData());

                            view.postSportsPlayMethodResult(sportsPlayMethodResult);
                        }else{
                            view.showMessage(sportsPlayMethodResult.getDescribe());
                        }*/
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
    public void postBet(String appRefer, String cate, String gid, String type, String active, String line_type, String odd_f_type, String gold, String ioradio_r_h, String rtype, String wtype) {
        Random random = new Random();
        String resultRandom="";
        for (int i=0;i<6;i++) {
            resultRandom += random.nextInt(10);
        }

        subscriptionHelper.add(RxHelper.addSugar(api.postBet(HGConstant.PRODUCT_PLATFORM,cate,gid,type,active,line_type,HGConstant.ODD_F_TYPE,gold,ioradio_r_h,rtype,wtype,resultRandom))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BetResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BetResult> response) {
                        if(response.isSuccess()&&response.getData()!=null){
                            view.postBetResult(response.getData());
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
