package com.hg3366.a3366.homepage;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.AGCheckAcountResult;
import com.hg3366.a3366.data.AGGameLoginResult;
import com.hg3366.a3366.data.BannerResult;
import com.hg3366.a3366.data.CPResult;
import com.hg3366.a3366.data.CheckAgLiveResult;
import com.hg3366.a3366.data.MaintainResult;
import com.hg3366.a3366.data.NoticeResult;
import com.hg3366.a3366.data.OnlineServiceResult;
import com.hg3366.a3366.data.QipaiResult;
import com.hg3366.a3366.data.ValidResult;

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
        public void postValidGift(String appRefer,String action);
        public void postValidGift2(String appRefer,String action);
        public void postMaintain();
        public void postBYGame(String appRefer, String gameid);
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
        public void postCPResult(CPResult cpResult);
        public void postValidGiftResult(ValidResult validResult);
        public void postValidGift2Result(ValidResult validResult);
        public void postMaintainResult(List<MaintainResult> maintainResult);
        public void postGoPlayGameResult(AGGameLoginResult agGameLoginResult);
    }

}
