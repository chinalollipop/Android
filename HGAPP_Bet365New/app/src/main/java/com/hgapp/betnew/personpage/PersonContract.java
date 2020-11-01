package com.hgapp.betnew.personpage;

import com.hgapp.betnew.base.IMessageView;
import com.hgapp.betnew.base.IPresenter;
import com.hgapp.betnew.base.IProgressView;
import com.hgapp.betnew.base.IView;
import com.hgapp.betnew.data.CPResult;
import com.hgapp.betnew.data.NoticeResult;
import com.hgapp.betnew.data.PersonBalanceResult;
import com.hgapp.betnew.data.PersonInformResult;
import com.hgapp.betnew.data.QipaiResult;

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
