package com.sunapp.bloc.personpage;

import com.sunapp.bloc.base.IMessageView;
import com.sunapp.bloc.base.IPresenter;
import com.sunapp.bloc.base.IProgressView;
import com.sunapp.bloc.base.IView;
import com.sunapp.bloc.data.AGGameLoginResult;
import com.sunapp.bloc.data.CPResult;
import com.sunapp.bloc.data.NoticeResult;
import com.sunapp.bloc.data.PersonBalanceResult;
import com.sunapp.bloc.data.PersonInformResult;
import com.sunapp.bloc.data.QipaiResult;

public interface PersonContract {
    public interface Presenter extends IPresenter
    {
        public void getPersonInform(String appRefer);
        public void getPersonBalance(String appRefer,String action);
        public void postQipai(String appRefer,String action);
        public void postHgQipai(String appRefer,String action);
        public void postNoticeList(String appRefer);
        public void postCP();
        public void logOut();
        public void postBYGame(String appRefer, String gameid);
    }
    public interface View extends IView<Presenter>,IMessageView,IProgressView
    {
        public void postPersonInformResult(PersonInformResult personInformResult);
        public void postPersonBalanceResult(PersonBalanceResult personBalance);
        public void postQipaiResult(QipaiResult qipaiResult);
        public void postHgQipaiResult(QipaiResult qipaiResult);
        public void postNoticeListResult(NoticeResult noticeResult);
        public void postPersonLogoutResult(String message);
        public void postCPResult(CPResult cpResult);
        public void postGoPlayGameResult(AGGameLoginResult agGameLoginResult);
    }

}
