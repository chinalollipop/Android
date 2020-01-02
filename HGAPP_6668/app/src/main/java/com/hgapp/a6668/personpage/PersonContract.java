package com.hgapp.a6668.personpage;

import com.hgapp.a6668.base.IMessageView;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.base.IProgressView;
import com.hgapp.a6668.base.IView;
import com.hgapp.a6668.data.CPResult;
import com.hgapp.a6668.data.NoticeResult;
import com.hgapp.a6668.data.PersonBalanceResult;
import com.hgapp.a6668.data.PersonInformResult;
import com.hgapp.a6668.data.QipaiResult;

public interface PersonContract {
    public interface Presenter extends IPresenter
    {
        public void getPersonInform(String appRefer);
        public void postNoticeList(String appRefer);
        public void getPersonBalance(String appRefer,String action);
        public void postQipai(String appRefer,String action);
        public void postHgQipai(String appRefer,String action);
        public void postCP();
        public void logOut();
    }
    public interface View extends IView<Presenter>,IMessageView,IProgressView
    {
        public void postNoticeListResult(NoticeResult noticeResult);
        public void postPersonInformResult(PersonInformResult personInformResult);
        public void postPersonBalanceResult(PersonBalanceResult personBalance);
        public void postQipaiResult(QipaiResult qipaiResult);
        public void postHgQipaiResult(QipaiResult qipaiResult);
        public void postPersonLogoutResult(String message);
        public void postCPResult(CPResult cpResult);
    }

}
