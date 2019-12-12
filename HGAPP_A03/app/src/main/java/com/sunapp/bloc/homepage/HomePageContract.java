package com.sunapp.bloc.homepage;

import com.sunapp.bloc.base.IMessageView;
import com.sunapp.bloc.base.IPresenter;
import com.sunapp.bloc.base.IProgressView;
import com.sunapp.bloc.base.IView;
import com.sunapp.bloc.data.AGCheckAcountResult;
import com.sunapp.bloc.data.AGGameLoginResult;
import com.sunapp.bloc.data.BannerResult;
import com.sunapp.bloc.data.CPResult;
import com.sunapp.bloc.data.CheckAgLiveResult;
import com.sunapp.bloc.data.MaintainResult;
import com.sunapp.bloc.data.NoticeResult;
import com.sunapp.bloc.data.OnlineServiceResult;
import com.sunapp.bloc.data.QipaiResult;
import com.sunapp.bloc.data.Sportcenter;
import com.sunapp.bloc.data.ValidResult;

import java.util.List;

public interface HomePageContract {

    public interface Presenter extends IPresenter
    {
        public void postOnlineService(String appRefer);
        public void postBanner(String appRefer);
        public void postNotice(String appRefer);
        public void postNoticeList(String appRefer);
        public void postAGLiveCheckRegister(String appRefer);
        public void postAGGameRegisterAccount(String appRefer,String action);
        public void postQipai(String appRefer,String action);
        public void postHGQipai(String appRefer,String action);
        public void postVGQipai(String appRefer,String action);
        public void postLYQipai(String appRefer,String action);
        public void postAviaQiPai(String appRefer,String action);
        public void postCP();
        public void postSportcenter();
        public void postValidGift(String appRefer,String action);
        public void postValidGift2(String appRefer,String action);
        public void postMaintain();
        public void postBYGame(String appRefer, String gameid);
        public void postOGGame(String appRefer, String gameid);
        public void postBBINGame(String appRefer, String gameid);
    }
    public interface View extends IView<HomePageContract.Presenter>,IMessageView,IProgressView
    {
        public void postOnlineServiceResult(OnlineServiceResult onlineServiceResult);
        public void postBannerResult(BannerResult bannerResult);
        public void postNoticeResult(NoticeResult noticeResult);
        public void postNoticeListResult(NoticeResult noticeResult);
        public void postAGLiveCheckRegisterResult(CheckAgLiveResult checkAgLiveResult);
        public void postAGGameRegisterAccountResult(AGCheckAcountResult agCheckAcountResult);
        public void postQipaiResult(QipaiResult qipaiResult);
        public void postHGQipaiResult(QipaiResult qipaiResult);
        public void postVGQipaiResult(QipaiResult qipaiResult);
        public void postLYQipaiResult(QipaiResult qipaiResult);
        public void postAviaQiPaiResult(QipaiResult qipaiResult);
        public void postOGResult(AGGameLoginResult qipaiResult);
        public void postCPResult(CPResult cpResult);
        public void postSportcenterResult(Sportcenter sportcenter);
        public void postValidGiftResult(ValidResult validResult);
        public void postValidGift2Result(ValidResult validResult);
        public void postMaintainResult(List<MaintainResult> maintainResult);
        public void postGoPlayGameResult(AGGameLoginResult agGameLoginResult);
    }

}
