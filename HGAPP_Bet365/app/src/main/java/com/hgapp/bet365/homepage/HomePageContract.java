package com.hgapp.bet365.homepage;

import com.hgapp.bet365.base.IMessageView;
import com.hgapp.bet365.base.IPresenter;
import com.hgapp.bet365.base.IProgressView;
import com.hgapp.bet365.base.IView;
import com.hgapp.bet365.data.AGGameLoginResult;
import com.hgapp.bet365.data.BannerResult;
import com.hgapp.bet365.data.CPResult;
import com.hgapp.bet365.data.GameNumResult;
import com.hgapp.bet365.data.MaintainResult;
import com.hgapp.bet365.data.NoticeResult;
import com.hgapp.bet365.data.QipaiResult;
import com.hgapp.bet365.data.ValidResult;

import java.util.List;

public interface HomePageContract {

    public interface Presenter extends IPresenter
    {
        public void postGameNum(String appRefer);
        public void postBanner(String appRefer);
        public void postNotice(String appRefer);
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
        public void postOGGame(String appRefer, String gameid);
        public void postBBINGame(String appRefer, String gameid);
        public void postThunFireGame(String appRefer, String gameid);
    }
    public interface View extends IView<HomePageContract.Presenter>,IMessageView,IProgressView
    {
        public void postGameNumResult(GameNumResult gameNumResult);
        public void postBannerResult(BannerResult bannerResult);
        public void postNoticeResult(NoticeResult noticeResult);
        public void postQipaiResult(QipaiResult qipaiResult);
        public void postHGQipaiResult(QipaiResult qipaiResult);
        public void postVGQipaiResult(QipaiResult qipaiResult);
        public void postLYQipaiResult(QipaiResult qipaiResult);
        public void postAviaQiPaiResult(QipaiResult qipaiResult);
        public void postOGResult(AGGameLoginResult qipaiResult);
        public void postCPResult(CPResult cpResult);
        public void postValidGiftResult(ValidResult validResult);
        public void postValidGift2Result(ValidResult validResult);
        public void postMaintainResult(List<MaintainResult> maintainResult);
        public void postGoPlayGameResult(AGGameLoginResult agGameLoginResult);
        public void postThunFireGameResult(QipaiResult qipaiResult);
    }

}
