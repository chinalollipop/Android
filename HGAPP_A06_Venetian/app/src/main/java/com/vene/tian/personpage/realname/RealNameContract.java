package com.vene.tian.personpage.realname;

import com.vene.tian.base.IMessageView;
import com.vene.tian.base.IPresenter;
import com.vene.tian.base.IProgressView;
import com.vene.tian.base.IView;

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
