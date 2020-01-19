package com.hgapp.m8.homepage.handicap.saiguo;

import com.hgapp.m8.base.IMessageView;
import com.hgapp.m8.base.IPresenter;
import com.hgapp.m8.base.IProgressView;
import com.hgapp.m8.base.IView;
import com.hgapp.m8.data.SaiGuoResult;

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
