package com.hg3366.a3366.personpage.realname;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;

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
