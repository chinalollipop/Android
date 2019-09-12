package com.venen.tian.homepage.events;

import com.venen.tian.base.IMessageView;
import com.venen.tian.base.IPresenter;
import com.venen.tian.base.IProgressView;
import com.venen.tian.base.IView;
import com.venen.tian.data.PersonBalanceResult;
import com.venen.tian.data.DownAppGiftResult;
import com.venen.tian.data.LuckGiftResult;
import com.venen.tian.data.ValidResult;

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
