package com.hgapp.betnhg.homepage.events;

import com.hgapp.betnhg.base.IMessageView;
import com.hgapp.betnhg.base.IPresenter;
import com.hgapp.betnhg.base.IProgressView;
import com.hgapp.betnhg.base.IView;
import com.hgapp.betnhg.data.PersonBalanceResult;
import com.hgapp.betnhg.data.DownAppGiftResult;
import com.hgapp.betnhg.data.LuckGiftResult;
import com.hgapp.betnhg.data.ValidResult;

public interface EventsContract {

    public interface Presenter extends IPresenter
    {
        public void postDownAppGift(String appRefer);
        public void postLuckGift(String appRefer,String action);
        public void postValidGift(String appRefer,String action);
        public void postNewYearRed(String appRefer,String action);
        public void postPersonBalance(String appRefer,String action);
    }
    public interface View extends IView<Presenter>,IMessageView,IProgressView
    {
        public void postDownAppGiftResult(DownAppGiftResult downAppGiftResult);
        public void postLuckGiftResult(LuckGiftResult luckGiftResult);
        public void postValidGiftResult(ValidResult validResult);
        public void postPersonBalanceResult(PersonBalanceResult personBalance);
    }

}
