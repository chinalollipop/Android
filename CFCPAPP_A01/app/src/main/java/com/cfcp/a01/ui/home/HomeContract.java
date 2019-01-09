package com.cfcp.a01.ui.home;

import com.cfcp.a01.base.IMessageView;
import com.cfcp.a01.base.IPresenter;
import com.cfcp.a01.base.IView;
import com.cfcp.a01.data.BannerResult;
import com.cfcp.a01.data.NoticeResult;
import com.cfcp.a01.data.WinNewsResult;

import java.util.List;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface HomeContract {
    public interface Presenter extends IPresenter {
        public void postBanner(String appRefer);
        public void postNotice(String appRefer,String type);
        public void postWinNews(String appRefer,String news);
        public void postLogout(String appRefer);
    }

    public interface View extends IView<Presenter>, IMessageView {

        public void postBannerResult(BannerResult bannerResult);
        public void postNoticeResult(List<NoticeResult> noticeResult);
        public void postWinNewsResult(WinNewsResult winNewsResult);
        public void postLogoutResult(String logoutResult);
    }
}
