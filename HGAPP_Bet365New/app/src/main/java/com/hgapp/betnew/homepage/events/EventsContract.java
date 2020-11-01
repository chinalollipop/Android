package com.hgapp.betnew.homepage.events;

import com.hgapp.betnew.base.IMessageView;
import com.hgapp.betnew.base.IPresenter;
import com.hgapp.betnew.base.IProgressView;
import com.hgapp.betnew.base.IView;
import com.hgapp.betnew.data.PersonBalanceResult;
import com.hgapp.betnew.data.DownAppGiftResult;
import com.hgapp.betnew.data.LuckGiftResult;
import com.hgapp.betnew.data.ValidResult;

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
