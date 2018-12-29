package com.qpweb.a01.ui.home;

import com.qpweb.a01.base.IMessageView;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.base.IView;
import com.qpweb.a01.data.BannerResult;
import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.data.NoticeResult;
import com.qpweb.a01.data.WinNewsResult;

import java.util.List;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface HomeContract {
    public interface Presenter extends IPresenter {
        public void postBanner(String appRefer);
        public void postNotice(String appRefer,String type);
        public void postWinNews(String appRefer,String news);
    }

    public interface View extends IView<Presenter>, IMessageView {

        public void postBannerResult(BannerResult bannerResult);
        public void postNoticeResult(List<NoticeResult> noticeResult);
        public void postWinNewsResult(WinNewsResult winNewsResult);
    }
}
