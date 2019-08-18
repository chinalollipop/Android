package com.sunapp.bloc.personpage.realname;

import com.sunapp.bloc.base.IMessageView;
import com.sunapp.bloc.base.IPresenter;
import com.sunapp.bloc.base.IProgressView;
import com.sunapp.bloc.base.IView;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface RealNameContract {

    public interface Presenter extends IPresenter
    {


        public void postUpdataRealName(String appRefer, String realname, String phone, String wechat, String birthday);
    }

    public interface View extends IView<Presenter> ,IMessageView,IProgressView
    {

        public void postRegisterMemberResult(String message);
    }
}
