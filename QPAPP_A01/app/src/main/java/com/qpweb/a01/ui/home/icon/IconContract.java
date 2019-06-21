package com.qpweb.a01.ui.home.icon;

import com.qpweb.a01.base.IMessageView;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.base.IView;
import com.qpweb.a01.data.ChangIconResult;
import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.data.NickNameResult;
import com.qpweb.a01.data.PSignatureResult;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface IconContract {

    public interface Presenter extends IPresenter {

        public void postChangeNickName(String appRefer, String action_type, String nickname);
        public void postChangeSignWords(String appRefer, String action_type, String personalizedsignature);
        public void postChangeIcon(String appRefer, String avatarid_save, String avatarid);
    }

    public interface View extends IView<Presenter>, IMessageView {

        public void postChangeNickNameResult(NickNameResult nickNameResult);
        public void postChangeSignWordsResult(PSignatureResult pSignatureResult);
        public void postChangeIconResult(ChangIconResult changIconResult);
    }
}
