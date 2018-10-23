package com.hgapp.a0086.homepage.handicap.leaguelist;

import com.hgapp.a0086.common.http.ResponseSubscriber;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.common.util.RxHelper;
import com.hgapp.a0086.common.util.SubscriptionHelper;
import com.hgapp.a0086.data.LeagueDetailSearchListResult;
import com.hgapp.a0086.data.LeagueSearchListResult;
import com.hgapp.a0086.data.LeagueSearchTimeResult;
import com.hgapp.common.util.Check;


public class LeagueSearchListPresenter implements LeagueSearchListContract.Presenter {


    private ILeagueSearchListApi api;
    private LeagueSearchListContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public LeagueSearchListPresenter(ILeagueSearchListApi api, LeagueSearchListContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void postLeagueSearchTime(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLeagueSearchTime(HGConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<LeagueSearchTimeResult>() {
                    @Override
                    public void success(LeagueSearchTimeResult response) {
                        if("200".equals(response.getStatus())){
                            //view.postOnlineServiceResult(response.getData());
                            if(!Check.isNull(response.getData())&&response.getData().size()>0){
                                view.postLeagueSearchTimeResult(response);
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
    public void postLeagueSearchList(String appRefer, String gtype,String showtype, String sorttype,String date) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLeagueSearchList(HGConstant.PRODUCT_PLATFORM,gtype,showtype,sorttype,date))
                .subscribe(new ResponseSubscriber<LeagueSearchListResult>() {
                    @Override
                    public void success(LeagueSearchListResult response) {
                        if("200".equals(response.getStatus())){
                            if(response.getData().size()>0){
                                view.postLeagueSearchListResult(response);
                            }else{
                                view.postLeagueSearchListNoDataResult(response.getDescribe());//"暂无数据，请稍后再试！"
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
    public void postLeaguePassSearchList(String appRefer, String gtype,String showtype, String sorttype,String date) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLeaguePassSearchList(HGConstant.PRODUCT_PLATFORM,gtype,showtype,sorttype,date))
                .subscribe(new ResponseSubscriber<LeagueSearchListResult>() {
                    @Override
                    public void success(LeagueSearchListResult response) {
                        if("200".equals(response.getStatus())){
                            if(!Check.isNull(response.getData())&&response.getData().size()>0){
                                view.postLeagueSearchListResult(response);
                            }else{
                                view.postLeagueSearchListNoDataResult(response.getDescribe());
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
    public void postLeagueSearchChampionList(String appRefer, String showtype, String FStype, String mtype) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLeagueSearchChampionList(HGConstant.PRODUCT_PLATFORM,showtype,FStype,mtype))
                .subscribe(new ResponseSubscriber<LeagueSearchListResult>() {
                    @Override
                    public void success(LeagueSearchListResult response) {
                        if("200".equals(response.getStatus())){
                            if(!Check.isNull(response.getData())&&response.getData().size()>0){
                                view.postLeagueSearchListResult(response);
                            }else{
                                view.postLeagueSearchListNoDataResult(response.getDescribe());
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
    public void postLeagueDetailSearchList(String appRefer, String type, String more, String gid) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLeagueDetailSearchList(HGConstant.PRODUCT_PLATFORM,type,more,gid))
                .subscribe(new ResponseSubscriber<LeagueDetailSearchListResult>() {
                    @Override
                    public void success(LeagueDetailSearchListResult response) {
                        if("200".equals(response.getStatus())){
                            if(response.getData().size()>0){
                                view.postLeagueDetailSearchListResult(response);
                            }else{
                                view.showMessage("暂无数据，请稍后再试！");
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
