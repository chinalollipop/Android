package com.hg3366.a3366.homepage.handicap.leaguedetail.zhbet;

import com.hg3366.a3366.common.http.ResponseSubscriber;
import com.hg3366.a3366.common.http.request.AppTextMessageResponseList;
import com.hg3366.a3366.common.util.HGConstant;
import com.hg3366.a3366.common.util.RxHelper;
import com.hg3366.a3366.common.util.SubscriptionHelper;
import com.hg3366.a3366.data.GameAllPlayZHResult;


public class PrepareBetZHApiPresenter implements PrepareBetZHApiContract.Presenter {


    private IPrepareBetZHApi api;
    private PrepareBetZHApiContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public PrepareBetZHApiPresenter(IPrepareBetZHApi api, PrepareBetZHApiContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void postGameAllBetsZH(String appRefer, String gtype, String sorttype, String mdate, String showtype,String M_League, String gid) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBetZH(HGConstant.PRODUCT_PLATFORM,gtype,showtype,mdate,showtype,M_League,gid))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<GameAllPlayZHResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<GameAllPlayZHResult> response) {
                        if(response.getStatus().equals("200")){
                            if(response.getData()!=null&&response.getData().size()>0){
                                view.postGameAllBetsZH(response.getData().get(0));
                            }else {
                                view.showMessage("暂无数据！");
                            }
                        }else{
                            view.showMessage(response.getDescribe());
                        }
                        //GameLog.log(response);
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
