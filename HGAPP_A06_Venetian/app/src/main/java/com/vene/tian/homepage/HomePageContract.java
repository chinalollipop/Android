package com.vene.tian.homepage;

import com.vene.tian.base.IMessageView;
import com.vene.tian.base.IPresenter;
import com.vene.tian.base.IProgressView;
import com.vene.tian.base.IView;
import com.vene.tian.data.AGCheckAcountResult;
import com.vene.tian.data.AGGameLoginResult;
import com.vene.tian.data.BannerResult;
import com.vene.tian.data.CPResult;
import com.vene.tian.data.CheckAgLiveResult;
import com.vene.tian.data.MaintainResult;
import com.vene.tian.data.NoticeResult;
import com.vene.tian.data.OnlineServiceResult;
import com.vene.tian.data.QipaiResult;
import com.vene.tian.data.ValidResult;

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
