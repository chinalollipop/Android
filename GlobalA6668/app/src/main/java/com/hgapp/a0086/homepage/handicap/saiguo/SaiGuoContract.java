package com.hgapp.a0086.homepage.handicap.saiguo;

import com.hgapp.a0086.base.IMessageView;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.base.IProgressView;
import com.hgapp.a0086.base.IView;
import com.hgapp.a0086.data.SaiGuoResult;

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
