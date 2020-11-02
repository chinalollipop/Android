package com.hgapp.betnhg.personpage;

import com.hgapp.betnhg.base.IMessageView;
import com.hgapp.betnhg.base.IPresenter;
import com.hgapp.betnhg.base.IProgressView;
import com.hgapp.betnhg.base.IView;
import com.hgapp.betnhg.data.CPResult;
import com.hgapp.betnhg.data.NoticeResult;
import com.hgapp.betnhg.data.PersonBalanceResult;
import com.hgapp.betnhg.data.PersonInformResult;
import com.hgapp.betnhg.data.QipaiResult;

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
