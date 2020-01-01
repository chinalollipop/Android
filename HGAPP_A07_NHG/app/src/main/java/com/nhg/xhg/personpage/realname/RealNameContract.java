package com.nhg.xhg.personpage.realname;

import com.nhg.xhg.base.IMessageView;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.base.IProgressView;
import com.nhg.xhg.base.IView;

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
