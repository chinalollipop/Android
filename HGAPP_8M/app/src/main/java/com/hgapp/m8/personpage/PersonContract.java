package com.hgapp.m8.personpage;

import com.hgapp.m8.base.IMessageView;
import com.hgapp.m8.base.IPresenter;
import com.hgapp.m8.base.IProgressView;
import com.hgapp.m8.base.IView;
import com.hgapp.m8.data.CPResult;
import com.hgapp.m8.data.NoticeResult;
import com.hgapp.m8.data.PersonBalanceResult;
import com.hgapp.m8.data.PersonInformResult;
import com.hgapp.m8.data.QipaiResult;

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
