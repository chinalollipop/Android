package com.sunapp.bloc.homepage.handicap.saiguo;

import com.sunapp.bloc.base.IMessageView;
import com.sunapp.bloc.base.IPresenter;
import com.sunapp.bloc.base.IProgressView;
import com.sunapp.bloc.base.IView;
import com.sunapp.bloc.data.SaiGuoResult;

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
