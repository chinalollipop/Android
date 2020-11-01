package com.hgapp.betnew.homepage;

import com.hgapp.betnew.base.IMessageView;
import com.hgapp.betnew.base.IPresenter;
import com.hgapp.betnew.base.IProgressView;
import com.hgapp.betnew.base.IView;
import com.hgapp.betnew.data.AGGameLoginResult;
import com.hgapp.betnew.data.BannerResult;
import com.hgapp.betnew.data.CPResult;
import com.hgapp.betnew.data.GameNumResult;
import com.hgapp.betnew.data.MaintainResult;
import com.hgapp.betnew.data.NoticeResult;
import com.hgapp.betnew.data.QipaiResult;
import com.hgapp.betnew.data.ValidResult;

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
