package com.venen.tian.personpage.realname;

import com.venen.tian.base.IMessageView;
import com.venen.tian.base.IPresenter;
import com.venen.tian.base.IProgressView;
import com.venen.tian.base.IView;

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
