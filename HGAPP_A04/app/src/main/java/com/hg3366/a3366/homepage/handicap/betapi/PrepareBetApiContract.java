package com.hg3366.a3366.homepage.handicap.betapi;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.BetResult;
import com.hg3366.a3366.data.GameAllPlayRBKResult;
import com.hg3366.a3366.data.GameAllPlayBKResult;
import com.hg3366.a3366.data.GameAllPlayFTResult;
import com.hg3366.a3366.data.GameAllPlayRFTResult;
import com.hg3366.a3366.data.PrepareBetResult;

public interface PrepareBetApiContract {
    public interface Presenter extends IPresenter{

        public void postGameAllBets(String appRefer, String gid, String gtype,String showtype);
        public void postGameAllBetsBK(String appRefer, String gid, String gtype,String showtype);
        public void postGameAllBetsRBK(String appRefer, String gid, String gtype,String showtype);
        public void postGameAllBetsFT(String appRefer, String gid, String gtype,String showtype);
        public void postGameAllBetsRFT(String appRefer, String gid, String gtype,String showtype);
        public void postPrepareBetApi(String appRefer, String order_method, String gid, String type, String wtype, String rtype,String odd_f_type, String error_flag, String order_type);
        public void postBetFTApi(String appRefer, String cate, String gid, String type, String active, String line_type, String odd_f_type, String gold, String ioradio_r_h, String rtype, String wtype, String autoOdd);
        public void postBetFTreApi(String appRefer, String cate, String gid, String type, String active, String line_type, String odd_f_type, String gold, String ioradio_r_h, String rtype, String wtype, String autoOdd);
        public void postBetFThreApi(String appRefer, String cate, String gid, String type, String active, String line_type, String odd_f_type, String gold, String ioradio_r_h, String rtype, String wtype, String autoOdd);
        public void postBetBKApi(String appRefer, String cate, String gid, String type, String active, String line_type, String odd_f_type, String gold, String ioradio_r_h, String rtype, String wtype, String autoOdd);
        public void postBetBKreApi(String appRefer, String cate, String gid, String type, String active, String line_type, String odd_f_type, String gold, String ioradio_r_h, String rtype, String wtype, String autoOdd);
        public void postBetChampionApi(String appRefer, String cate, String gid, String type, String active, String line_type, String odd_f_type, String gold, String ioradio_r_h, String rtype, String wtype, String autoOdd);

    }

    public interface View extends IView<PrepareBetApiContract.Presenter>,IMessageView,IProgressView {
        public void postGameAllBetsBKResult(GameAllPlayBKResult gameAllPlayBKResult);
        public void postGameAllBetsRBKResult(GameAllPlayRBKResult gameAllPlayRBKResult);
        public void postGameAllBetsFTResult(GameAllPlayFTResult gameAllPlayFTResult);
        public void postGameAllBetsRFTResult(GameAllPlayRFTResult gameAllPlayRFTResult);
        public void postGameAllBetsFTFailResult(String message);
        public void postGameAllBetsResult(GameAllPlayRBKResult gameAllBetsResult);
        public void postPrepareBetApiResult(PrepareBetResult prepareBetResult);
        public void postBetApiResult(BetResult betResult);
        public void postBetApiFailResult(String message);
    }
}
