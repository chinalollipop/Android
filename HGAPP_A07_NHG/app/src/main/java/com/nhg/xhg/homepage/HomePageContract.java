package com.nhg.xhg.homepage;

import com.nhg.xhg.base.IMessageView;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.base.IProgressView;
import com.nhg.xhg.base.IView;
import com.nhg.xhg.data.AGCheckAcountResult;
import com.nhg.xhg.data.AGGameLoginResult;
import com.nhg.xhg.data.BannerResult;
import com.nhg.xhg.data.CPResult;
import com.nhg.xhg.data.CheckAgLiveResult;
import com.nhg.xhg.data.MaintainResult;
import com.nhg.xhg.data.NoticeResult;
import com.nhg.xhg.data.OnlineServiceResult;
import com.nhg.xhg.data.QipaiResult;
import com.nhg.xhg.data.Sportcenter;
import com.nhg.xhg.data.ValidResult;

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
