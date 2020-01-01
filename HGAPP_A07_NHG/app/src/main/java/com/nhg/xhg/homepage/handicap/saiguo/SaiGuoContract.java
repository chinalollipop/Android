package com.nhg.xhg.homepage.handicap.saiguo;

import com.nhg.xhg.base.IMessageView;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.base.IProgressView;
import com.nhg.xhg.base.IView;
import com.nhg.xhg.data.SaiGuoResult;

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
