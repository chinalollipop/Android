package com.hg3366.a3366.homepage.aglist;

import com.hg3366.a3366.common.http.ResponseSubscriber;
import com.hg3366.a3366.common.http.request.AppTextMessageResponse;
import com.hg3366.a3366.common.http.request.AppTextMessageResponseList;
import com.hg3366.a3366.common.util.HGConstant;
import com.hg3366.a3366.common.util.RxHelper;
import com.hg3366.a3366.common.util.SubscriptionHelper;
import com.hg3366.a3366.data.AGGameLoginResult;
import com.hg3366.a3366.data.AGLiveResult;
import com.hg3366.a3366.data.CheckAgLiveResult;
import com.hg3366.a3366.data.PersonBalanceResult;


public class AGListPresenter implements AGListContract.Presenter {


    private IAGListApi api;
    private AGListContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public AGListPresenter(IAGListApi api, AGListContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void postPersonBalance(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalance(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<PersonBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<PersonBalanceResult> response) {
                        if(response.isSuccess())
                        {
                            view.postPersonBalanceResult(response.getData().get(0));
                        }
                        else
                        {
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
    public void postMGPersonBalance(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postMGPersonBalance(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<PersonBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<PersonBalanceResult> response) {
                        if(response.isSuccess())
                        {
                            view.postMGPersonBalanceResult(response.getData().get(0));
                        }
                        else
                        {
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
    public void postCQPersonBalance(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postCQPersonBalance(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<PersonBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<PersonBalanceResult> response) {
                        if(response.isSuccess())
                        {
                            view.postCQPersonBalanceResult(response.getData().get(0));
                        }
                        else
                        {
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
    public void postMWPersonBalance(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postMWPersonBalance(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<PersonBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<PersonBalanceResult> response) {
                        if(response.isSuccess())
                        {
                            view.postMWPersonBalanceResult(response.getData().get(0));
                        }
                        else
                        {
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
    public void postAGGameList(String appRefer, String uid, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postAGGameList(HGConstant.PRODUCT_PLATFORM,uid,action))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<AGLiveResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<AGLiveResult> response) {
                        if(response.isSuccess()){
                            if(null!=response.getData()){
                                view.postAGGameResult(response.getData());
                            }
                        }else{
                            view.postsMessageGameResult(response.getDescribe());
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
    public void postMGGameList(String appRefer, String uid, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postMGGameList(HGConstant.PRODUCT_PLATFORM,"mgDianziGames"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<AGLiveResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<AGLiveResult> response) {
                        if(response.isSuccess()){
                            if(null!=response.getData()){
                                view.postAGGameResult(response.getData());
                            }
                        }else{
                            view.postsMessageGameResult(response.getDescribe());
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
    public void postCQGameList(String appRefer, String uid, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postCQGameList(HGConstant.PRODUCT_PLATFORM,"cqDianziGames"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<AGLiveResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<AGLiveResult> response) {
                        if(response.isSuccess()){
                            if(null!=response.getData()){
                                view.postAGGameResult(response.getData());
                            }
                        }else{
                            view.postsMessageGameResult(response.getDescribe());
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
    public void postMWGameList(String appRefer, String uid, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postMWGameList(HGConstant.PRODUCT_PLATFORM,"mwGames"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<AGLiveResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<AGLiveResult> response) {
                        if(response.isSuccess()){
                            if(null!=response.getData()){
                                view.postAGGameResult(response.getData());
                            }
                        }else{
                            view.postsMessageGameResult(response.getDescribe());
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
    public void postCheckAgLiveAccount(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postCheckAgLiveAccount(HGConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CheckAgLiveResult>>() {
            @Override
            public void success(AppTextMessageResponse<CheckAgLiveResult> response) {
                if(response.isSuccess())
                {
                    view.postCheckAgLiveAccountResult(response.getData());
                }
                else
                {
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
    public void postCheckAgGameAccount(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postCheckAgGameAccount(HGConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CheckAgLiveResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<CheckAgLiveResult> response) {
                        if(response.isSuccess())
                        {
                            view.postCheckAgGameAccountResult(response.getData());
                        }
                        else
                        {
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
    public void postGoPlayGame(String appRefer, String gameid) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLoginGame(HGConstant.PRODUCT_PLATFORM,gameid))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<AGGameLoginResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<AGGameLoginResult> response) {
                        if(response.isSuccess())
                        {
                            view.postGoPlayGameResult(response.getData());
                        }
                        else
                        {
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
    public void postGoPlayGameMG(String appRefer, String gameid) {
        subscriptionHelper.add(RxHelper.addSugar(api.postMGLoginGame(HGConstant.PRODUCT_PLATFORM,gameid,"getLaunchGameUrl"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<AGGameLoginResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<AGGameLoginResult> response) {
                        if(response.isSuccess())
                        {
                            view.postGoPlayGameResult(response.getData());
                        }
                        else
                        {
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
    public void postGoPlayGameCQ(String appRefer, String gameid) {
        subscriptionHelper.add(RxHelper.addSugar(api.postCQLoginGame(HGConstant.PRODUCT_PLATFORM,gameid,"getLaunchGameUrl"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<AGGameLoginResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<AGGameLoginResult> response) {
                        if(response.isSuccess())
                        {
                            view.postGoPlayGameResult(response.getData());
                        }
                        else
                        {
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
    public void postGoPlayGameMW(String appRefer, String gameid) {
        subscriptionHelper.add(RxHelper.addSugar(api.postMWLoginGame(HGConstant.PRODUCT_PLATFORM,gameid,"appGameLobby"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<AGGameLoginResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<AGGameLoginResult> response) {
                        if(response.isSuccess())
                        {
                            view.postGoPlayGameResult(response.getData().get(0));
                        }
                        else
                        {
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
    public void postFGPersonBalance(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postFGPersonBalance(HGConstant.PRODUCT_PLATFORM,"b"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<PersonBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<PersonBalanceResult> response) {
                        if(response.isSuccess())
                        {
                            view.postFGPersonBalanceResult(response.getData().get(0));
                        }
                        else
                        {
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
    public void postFGGameList(String appRefer, String uid, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postFGGameList(HGConstant.PRODUCT_PLATFORM,"fgGames"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<AGLiveResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<AGLiveResult> response) {
                        if(response.isSuccess()){
                            if(null!=response.getData()){
                                view.postAGGameResult(response.getData());
                            }
                        }else{
                            view.postsMessageGameResult(response.getDescribe());
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
    public void postGoPlayGameFG(String appRefer, String gameid) {
        subscriptionHelper.add(RxHelper.addSugar(api.postFGLoginGame(HGConstant.PRODUCT_PLATFORM,gameid,"getLaunchGameUrl"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<AGGameLoginResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<AGGameLoginResult> response) {
                        if(response.isSuccess())
                        {
                            view.postGoPlayGameResult(response.getData().get(0));
                        }
                        else
                        {
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
    public void postCheckAgAccount(String appRefer, String uid, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postCheckAgAccount(HGConstant.PRODUCT_PLATFORM,uid,action))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<CheckAgLiveResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<CheckAgLiveResult> response) {
                        if(response.isSuccess()){
                            if(null!=response.getData()){
                                view.postCheckAgAccountResult(response.getData().get(0));
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
    public void postCreateAgAccount(String appRefer, String uid, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postCreateAgAccount(HGConstant.PRODUCT_PLATFORM,uid,action))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<CheckAgLiveResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<CheckAgLiveResult> response) {
                        if(response.isSuccess())
                        {
                            view.postCheckAgAccountResult(response.getData().get(0));
                        }
                        else
                        {
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
