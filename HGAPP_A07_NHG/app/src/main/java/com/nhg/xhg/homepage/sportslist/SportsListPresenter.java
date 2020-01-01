package com.nhg.xhg.homepage.sportslist;

import com.nhg.xhg.common.http.ResponseSubscriber;
import com.nhg.xhg.common.util.HGConstant;
import com.nhg.xhg.common.util.RxHelper;
import com.nhg.xhg.common.util.SubscriptionHelper;
import com.nhg.xhg.data.SportsListResult;


public class SportsListPresenter implements SportsListContract.Presenter {


    private ISportsListApi api;
    private SportsListContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public SportsListPresenter(ISportsListApi api, SportsListContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void postSportsList(String appRefer,String type, String more) {
        subscriptionHelper.add(RxHelper.addSugar(api.postSprotsList(HGConstant.PRODUCT_PLATFORM,type,more))
                .subscribe(new ResponseSubscriber<SportsListResult>() {
                    @Override
                    public void success(SportsListResult response) {
                        if("200".equals(response.getStatus())){
                            //view.postOnlineServiceResult(response.getData());
                            if(response.getData().size()>0){
                                view.postSportsListResultResult(response);
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
    public void postSportsListFU(String appRefer, String gtype,String showtype, String sorttype,String date) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLeagueSearchList(HGConstant.PRODUCT_PLATFORM,gtype,showtype,sorttype,date))
                .subscribe(new ResponseSubscriber<SportsListResult>() {
                    @Override
                    public void success(SportsListResult response) {
                        if("200".equals(response.getStatus())){
                                //view.showMessage("暂时无数据，请稍后再试！");
                                //view.postOnlineServiceResult(response.getData());
                                view.postSportsListResultResultFU(response);
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
    public void postSportsListFTs(String appRefer, String gtype,String showtype, String sorttype,String date) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLeagueSearchList(HGConstant.PRODUCT_PLATFORM,gtype,showtype,sorttype,date))
                .subscribe(new ResponseSubscriber<SportsListResult>() {
                    @Override
                    public void success(SportsListResult response) {
                        if("200".equals(response.getStatus())){
                                //view.showMessage("暂时无数据，请稍后再试！");
                                //view.postOnlineServiceResult(response.getData());
                                view.postSportsListResultResultFTs(response);
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
    public void postSportsListFTr(String appRefer, String gtype,String showtype, String sorttype,String date) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLeagueSearchList(HGConstant.PRODUCT_PLATFORM,gtype,showtype,sorttype,date))
                .subscribe(new ResponseSubscriber<SportsListResult>() {
                    @Override
                    public void success(SportsListResult response) {
                        if("200".equals(response.getStatus())){
                                //view.showMessage("暂时无数据，请稍后再试！");
                                //view.postOnlineServiceResult(response.getData());
                                view.postSportsListResultResultFTr(response);
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
    public void postSportsListBU(String appRefer, String gtype,String showtype, String sorttype,String date) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLeagueSearchList(HGConstant.PRODUCT_PLATFORM,gtype,showtype,sorttype,date))
                .subscribe(new ResponseSubscriber<SportsListResult>() {
                    @Override
                    public void success(SportsListResult response) {
                        if("200".equals(response.getStatus())){
                               // view.showMessage("暂时无数据，请稍后再试！");
                                //view.postOnlineServiceResult(response.getData());
                                view.postSportsListResultResultBU(response);
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
    public void postSportsListBKs(String appRefer, String gtype,String showtype, String sorttype,String date) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLeagueSearchList(HGConstant.PRODUCT_PLATFORM,gtype,showtype,sorttype,date))
                .subscribe(new ResponseSubscriber<SportsListResult>() {
                    @Override
                    public void success(SportsListResult response) {
                        if("200".equals(response.getStatus())){
                               // view.showMessage("暂时无数据，请稍后再试！");
                                //view.postOnlineServiceResult(response.getData());
                                view.postSportsListResultResultBKs(response);
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
    public void postSportsListBKr(String appRefer, String gtype,String showtype, String sorttype,String date) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLeagueSearchList(HGConstant.PRODUCT_PLATFORM,gtype,showtype,sorttype,date))
                .subscribe(new ResponseSubscriber<SportsListResult>() {
                    @Override
                    public void success(SportsListResult response) {
                        if("200".equals(response.getStatus())){
                               // view.showMessage("暂时无数据，请稍后再试！");
                                //view.postOnlineServiceResult(response.getData());
                                view.postSportsListResultResultBKr(response);
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
