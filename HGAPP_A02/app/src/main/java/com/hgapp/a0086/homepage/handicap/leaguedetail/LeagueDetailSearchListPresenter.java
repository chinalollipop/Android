package com.hgapp.a0086.homepage.handicap.leaguedetail;

import com.hgapp.a0086.common.http.ResponseSubscriber;
import com.hgapp.a0086.common.http.request.AppTextMessageResponse;
import com.hgapp.a0086.common.http.request.AppTextMessageResponseList;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.common.util.RxHelper;
import com.hgapp.a0086.common.util.SubscriptionHelper;
import com.hgapp.a0086.data.BetResult;
import com.hgapp.a0086.data.ComPassSearchListResult;
import com.hgapp.a0086.data.LeagueDetailSearchListResult;
import com.hgapp.a0086.data.PrepareBetResult;
import com.hgapp.common.util.Check;

import java.util.Random;


public class LeagueDetailSearchListPresenter implements LeagueDetailSearchListContract.Presenter {


    private ILeagueDetailSearchListApi api;
    private LeagueDetailSearchListContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public LeagueDetailSearchListPresenter(ILeagueDetailSearchListApi api, LeagueDetailSearchListContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void postLeagueDetailSearchList(String appRefer, String type, String more, String gid) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLeagueDetailSearchList(HGConstant.PRODUCT_PLATFORM,type,more,gid))
                .subscribe(new ResponseSubscriber<LeagueDetailSearchListResult>() {
                    @Override
                    public void success(LeagueDetailSearchListResult response) {
                        if("200".equals(response.getStatus())){
                            if(!Check.isNull(response.getData())&&response.getData().size()>0){
                                view.postLeagueDetailSearchListResult(response);
                            }else{
                                view.postLeagueDetailSearchListNoDataResult("暂无数据，请稍后再试！");
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
    public void postComPassSearchList(String appRefer, String gtype, String sorttype, String mdate, String showtype, String M_league) {
        subscriptionHelper.add(RxHelper.addSugar(api.postComPassSearchList(HGConstant.PRODUCT_PLATFORM,gtype,sorttype,mdate,showtype,M_league))
                .subscribe(new ResponseSubscriber<ComPassSearchListResult>() {
                    @Override
                    public void success(ComPassSearchListResult response) {
                        if("200".equals(response.getStatus())){
                            if(!Check.isNull(response.getData())&&response.getData().size()>0){
                                view.postComPassSearchListResult(response);
                            }else{
                                view.postLeagueDetailSearchListNoDataResult("暂无数据，请稍后再试！");
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
    public void postPrepareBetApi(String appRefer, String order_method, String gid, String type, String wtype, String rtype, String odd_f_type, String error_flag, String order_type,String isMaster) {
            subscriptionHelper.add(RxHelper.addSugar(api.postPrepareBet(HGConstant.PRODUCT_PLATFORM,order_method,gid,type,wtype,rtype,HGConstant.ODD_F_TYPE,error_flag,order_type,isMaster))
                    .subscribe(new ResponseSubscriber<AppTextMessageResponseList<PrepareBetResult>>() {
                        @Override
                        public void success(AppTextMessageResponseList<PrepareBetResult> response) {
                            if(response.isSuccess()){
                                if(null!=response.getData()){
                                    view.postPrepareBetApiResult(response.getData().get(0));
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
    public void postBetApi(String appRefer, String cate, String gid, String type, String active, String line_type, String odd_f_type, String gold, String ioradio_r_h, String rtype, String wtype) {
        Random random = new Random();
        String resultRandom="";
        for (int i=0;i<6;i++) {
            resultRandom += random.nextInt(10);
        }
        subscriptionHelper.add(RxHelper.addSugar(api.postBet(HGConstant.PRODUCT_PLATFORM,cate,gid,type,active,line_type,HGConstant.ODD_F_TYPE,gold,ioradio_r_h,rtype,wtype,resultRandom))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BetResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BetResult> response) {
                        if(response.isSuccess()){
                            view.postBetApiResult(response.getData());
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
