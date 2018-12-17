package com.hgapp.a6668.homepage;

import com.hgapp.a6668.base.IMessageView;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.base.IProgressView;
import com.hgapp.a6668.base.IView;
import com.hgapp.a6668.data.AGCheckAcountResult;
import com.hgapp.a6668.data.BannerResult;
import com.hgapp.a6668.data.CPResult;
import com.hgapp.a6668.data.CheckAgLiveResult;
import com.hgapp.a6668.data.MaintainResult;
import com.hgapp.a6668.data.NoticeResult;
import com.hgapp.a6668.data.OnlineServiceResult;
import com.hgapp.a6668.data.QipaiResult;
import com.hgapp.a6668.data.ValidResult;

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
        public void postCP();
        public void postValidGift(String appRefer,String action);
        public void postValidGift2(String appRefer,String action);
        public void postMaintain();
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
        public void postCPResult(CPResult cpResult);
        public void postValidGiftResult(ValidResult validResult);
        public void postValidGift2Result(ValidResult validResult);
        public void postMaintainResult(List<MaintainResult> maintainResult);
    }

}
