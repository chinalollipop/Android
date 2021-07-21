package com.hgapp.a0086.homepage.handicap.betapi;

import com.hgapp.a0086.common.http.ResponseSubscriber;
import com.hgapp.a0086.common.http.request.AppTextMessageResponse;
import com.hgapp.a0086.common.http.request.AppTextMessageResponseList;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.common.util.RxHelper;
import com.hgapp.a0086.common.util.SubscriptionHelper;
import com.hgapp.a0086.data.BetResult;
import com.hgapp.a0086.data.GameAllPlayRBKResult;
import com.hgapp.a0086.data.GameAllPlayBKResult;
import com.hgapp.a0086.data.GameAllPlayFTResult;
import com.hgapp.a0086.data.GameAllPlayRFTResult;
import com.hgapp.a0086.data.PrepareBetResult;

import java.util.Random;


public class PrepareBetApiPresenter implements PrepareBetApiContract.Presenter {


    private IPrepareBetApi api;
    private PrepareBetApiContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public PrepareBetApiPresenter(IPrepareBetApi api, PrepareBetApiContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void postGameAllBets(String appRefer, String gid,String gtype, String showtype) {
        subscriptionHelper.add(RxHelper.addSugar(api.postGameAllBets(HGConstant.PRODUCT_PLATFORM,gid,gtype,showtype))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<GameAllPlayRBKResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<GameAllPlayRBKResult> response) {
                        if(response.getStatus().equals("200")){
                            if(response.getData()!=null){
                                view.postGameAllBetsResult(response.getData());
                            }else{
                                view.postGameAllBetsFTFailResult(response.getDescribe());
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
    public void postGameAllBetsBK(String appRefer,final String gid, String gtype, String showtype) {
        subscriptionHelper.add(RxHelper.addSugar(api.postGameAllBetsBK(HGConstant.PRODUCT_PLATFORM,gid,gtype,showtype))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<GameAllPlayBKResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<GameAllPlayBKResult> response) {
                        if(response.getStatus().equals("200")){
                            if(response.getData()!=null){
                                for(int k=0;k<response.getData().size();k++){
                                    if(gid.equals(response.getData().get(k).getGid())){
                                        view.postGameAllBetsBKResult(response.getData().get(k));
                                        return;
                                    }
                                }
                            }else{
                                view.postGameAllBetsFTFailResult(response.getDescribe());
                            }
                        }else{
                            view.postGameAllBetsFTFailResult(response.getDescribe());
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
    public void postGameAllBetsRBK(String appRefer, final String gid, String gtype, String showtype) {
        subscriptionHelper.add(RxHelper.addSugar(api.postGameAllBetsRBK(HGConstant.PRODUCT_PLATFORM,gid,gtype,showtype))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<GameAllPlayRBKResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<GameAllPlayRBKResult> response) {
                        if(response.getStatus().equals("200")){
                            if(response.getData()!=null){
                                for(int k=0;k<response.getData().size();k++){
                                    if(gid.equals(response.getData().get(k).getGid())){
                                        view.postGameAllBetsRBKResult(response.getData().get(k));
                                        return;
                                    }
                                }
                            }else{
                                view.postGameAllBetsFTFailResult(response.getDescribe());
                            }
                        }else{
                            view.postGameAllBetsFTFailResult(response.getDescribe());
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
    public void postGameAllBetsFT(String appRefer, String gid, String gtype, String showtype) {
        subscriptionHelper.add(RxHelper.addSugar(api.postGameAllBetsFT(HGConstant.PRODUCT_PLATFORM,gid,gtype,showtype))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<GameAllPlayFTResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<GameAllPlayFTResult> response) {
                        if(response.getStatus().equals("200")){
                            if(response.getData()!=null){
                                view.postGameAllBetsFTResult(response.getData().get(0));
                            }else{
                                view.postGameAllBetsFTFailResult(response.getDescribe());
                            }
                        }else{
                            view.postGameAllBetsFTFailResult(response.getDescribe());
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
    public void postGameAllBetsRFT(String appRefer, String gid, String gtype, String showtype, String isMaster) {
        subscriptionHelper.add(RxHelper.addSugar(api.postGameAllBetsRFT(HGConstant.PRODUCT_PLATFORM,gid,gtype,showtype,isMaster))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<GameAllPlayRFTResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<GameAllPlayRFTResult> response) {
                        if(response.getStatus().equals("200")){
                            if(response.getData()!=null){
                                view.postGameAllBetsRFTResult(response.getData().get(0));
                            }else{
                                view.postGameAllBetsFTFailResult(response.getDescribe());
                            }
                        }else{
                            view.postGameAllBetsFTFailResult(response.getDescribe());
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
    public void postBetFTApi(String appRefer, String cate, String gid, String type, String active, String line_type, String odd_f_type, String gold, String ioradio_r_h, String rtype, String wtype,String autoOdd) {
        Random random = new Random();
        String resultRandom="";
        for (int i=0;i<6;i++) {
            resultRandom += random.nextInt(10);
        }
        subscriptionHelper.add(RxHelper.addSugar(api.postBetFT(HGConstant.PRODUCT_PLATFORM,cate,gid,type,active,line_type,HGConstant.ODD_F_TYPE,gold,ioradio_r_h,rtype,wtype,autoOdd,resultRandom))
                .subscribe(new ResponseSubscriber<BetResult>() {
                    @Override
                    public void success(BetResult response) {
                        if(response.getStatus().equals("200")){
                            view.postBetApiResult(response);
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
    public void postBetFTreApi(String appRefer, String cate, String gid, String type, String active, String line_type, String odd_f_type, String gold, String ioradio_r_h, String rtype, String wtype,String autoOdd) {
        Random random = new Random();
        String resultRandom="";
        for (int i=0;i<6;i++) {
            resultRandom += random.nextInt(10);
        }
        subscriptionHelper.add(RxHelper.addSugar(api.postBetFTre(HGConstant.PRODUCT_PLATFORM,cate,gid,type,active,line_type,HGConstant.ODD_F_TYPE,gold,ioradio_r_h,rtype,wtype,autoOdd,resultRandom))
                .subscribe(new ResponseSubscriber<BetResult>() {
                    @Override
                    public void success(BetResult response) {
                        if(response.getStatus().equals("200")){
                            view.postBetApiResult(response);
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
    public void postBetFThreApi(String appRefer, String cate, String gid, String type, String active, String line_type, String odd_f_type, String gold, String ioradio_r_h, String rtype, String wtype,String autoOdd) {
        Random random = new Random();
        String resultRandom="";
        for (int i=0;i<6;i++) {
            resultRandom += random.nextInt(10);
        }
        subscriptionHelper.add(RxHelper.addSugar(api.postBetFThre(HGConstant.PRODUCT_PLATFORM,cate,gid,type,active,line_type,HGConstant.ODD_F_TYPE,gold,ioradio_r_h,rtype,wtype,autoOdd,resultRandom))
                .subscribe(new ResponseSubscriber<BetResult>() {
                    @Override
                    public void success(BetResult response) {
                        if(response.getStatus().equals("200")){
                            view.postBetApiResult(response);
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
    public void postBetBKApi(String appRefer, String cate, String gid, String type, String active, String line_type, String odd_f_type, String gold, String ioradio_r_h, String rtype, String wtype,String autoOdd) {
        Random random = new Random();
        String resultRandom="";
        for (int i=0;i<6;i++) {
            resultRandom += random.nextInt(10);
        }
        subscriptionHelper.add(RxHelper.addSugar(api.postBetBK(HGConstant.PRODUCT_PLATFORM,cate,gid,type,active,line_type,HGConstant.ODD_F_TYPE,gold,ioradio_r_h,rtype,wtype,autoOdd,resultRandom))
                .subscribe(new ResponseSubscriber<BetResult>() {
                    @Override
                    public void success(BetResult response) {
                        if(response.getStatus().equals("200")){
                            view.postBetApiResult(response);
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
    public void postBetBKreApi(String appRefer, String cate, String gid, String type, String active, String line_type, String odd_f_type, String gold, String ioradio_r_h, String rtype, String wtype,String autoOdd) {
        Random random = new Random();
        String resultRandom="";
        for (int i=0;i<6;i++) {
            resultRandom += random.nextInt(10);
        }
        subscriptionHelper.add(RxHelper.addSugar(api.postBetBKre(HGConstant.PRODUCT_PLATFORM,cate,gid,type,active,line_type,HGConstant.ODD_F_TYPE,gold,ioradio_r_h,rtype,wtype,autoOdd,resultRandom))
                .subscribe(new ResponseSubscriber<BetResult>() {
                    @Override
                    public void success(BetResult response) {
                        if(response.getStatus().equals("200")){
                            view.postBetApiResult(response);
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
    public void postBetChampionApi(String appRefer, String cate, String gid, String type, String active, String line_type, String odd_f_type, String gold, String ioradio_r_h, String rtype, String wtype,String autoOdd) {
        Random random = new Random();
        String resultRandom="";
        for (int i=0;i<6;i++) {
            resultRandom += random.nextInt(10);
        }
        subscriptionHelper.add(RxHelper.addSugar(api.postBetChampionFT(HGConstant.PRODUCT_PLATFORM,cate,gid,type,active,line_type,HGConstant.ODD_F_TYPE,gold,ioradio_r_h,rtype,wtype,autoOdd,resultRandom))
                .subscribe(new ResponseSubscriber<BetResult>() {
                    @Override
                    public void success(BetResult response) {
                        if(response.getStatus().equals("200")){
                            view.postBetApiResult(response);
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
