package com.hgapp.bet365.homepage.handicap.saiguo;

import com.hgapp.bet365.base.IMessageView;
import com.hgapp.bet365.base.IPresenter;
import com.hgapp.bet365.base.IProgressView;
import com.hgapp.bet365.base.IView;
import com.hgapp.bet365.data.SaiGuoResult;

public interface SaiGuoContract {
    public interface Presenter extends IPresenter
    {
        public void postSaiGuoList(String appRefer, String game_type, String list_data);
    }
    public interface View extends IView<SaiGuoContract.Presenter>,IMessageView,IProgressView
    {
        public void postSaiGuoResult(SaiGuoResult saiGuoResult);
    }
}
