package com.hgapp.a6668.homepage.cplist.bet;

import com.hgapp.a6668.common.http.ResponseSubscriber;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.util.RxHelper;
import com.hgapp.a6668.common.util.SubscriptionHelper;
import com.hgapp.a6668.data.BetResult;
import com.hgapp.a6668.data.CPBetResult;

import java.util.Random;


public class CpBetApiPresenter implements CpBetApiContract.Presenter {
    private ICpBetApi api;
    private CpBetApiContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public CpBetApiPresenter(ICpBetApi api, CpBetApiContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void postCpBets(String game_code, String round, String totalNums, String totalMoney, String number, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postCpBets(game_code,round,totalNums,totalMoney,number,x_session_token))
                .subscribe(new ResponseSubscriber<CPBetResult>() {
                    @Override
                    public void success(CPBetResult response) {
                        if(response.getCode().equals("200")){
                            view.postCpBetResult(response);
                        }else{
                            view.showMessage(response.getMsg());
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
