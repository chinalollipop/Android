package com.hg3366.a3366.homepage.handicap.leaguelist.championlist;

import com.hg3366.a3366.common.http.ResponseSubscriber;
import com.hg3366.a3366.common.http.request.AppTextMessageResponseList;
import com.hg3366.a3366.common.util.HGConstant;
import com.hg3366.a3366.common.util.RxHelper;
import com.hg3366.a3366.common.util.SubscriptionHelper;
import com.hg3366.a3366.data.ChampionDetailListResult;
import com.hg3366.a3366.data.PrepareBetResult;


public class ChampionDetailListPresenter implements ChampionDetailListContract.Presenter {


    private IChampionDetailListApi api;
    private ChampionDetailListContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public ChampionDetailListPresenter(IChampionDetailListApi api, ChampionDetailListContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }


    @Override
    public void postLeagueSearchChampionList(String appRefer, String showtype, String FStype, String mtype, String M_League) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLeagueSearchChampionList(HGConstant.PRODUCT_PLATFORM,showtype,FStype,mtype,M_League))
                .subscribe(new ResponseSubscriber<ChampionDetailListResult>() {
                    @Override
                    public void success(ChampionDetailListResult response) {
                        if("200".equals(response.getStatus())){
                            if(response.getData().size()>0){
                                view.postLeagueSearchChampionListResult(response);
                            }else{
                                view.postLeagueSearchChampionListNoDataResult("暂无数据，请稍后再试！");
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
    public void postPrepareBetApi(String appRefer, String order_method, String gid, String type, String wtype, String rtype, String odd_f_type, String error_flag, String order_type) {
            subscriptionHelper.add(RxHelper.addSugar(api.postPrepareBet(HGConstant.PRODUCT_PLATFORM,order_method,gid,type,wtype,rtype,HGConstant.ODD_F_TYPE,error_flag,order_type))
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
    public void start() {

    }

    @Override
    public void destroy() {

    }
}
