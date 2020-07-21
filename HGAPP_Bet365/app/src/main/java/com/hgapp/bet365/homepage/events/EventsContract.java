package com.hgapp.bet365.homepage.events;

import com.hgapp.bet365.base.IMessageView;
import com.hgapp.bet365.base.IPresenter;
import com.hgapp.bet365.base.IProgressView;
import com.hgapp.bet365.base.IView;
import com.hgapp.bet365.data.PersonBalanceResult;
import com.hgapp.bet365.data.DownAppGiftResult;
import com.hgapp.bet365.data.LuckGiftResult;
import com.hgapp.bet365.data.ValidResult;

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
