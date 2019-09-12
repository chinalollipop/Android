package com.vene.tian.personpage;

import com.vene.tian.base.IMessageView;
import com.vene.tian.base.IPresenter;
import com.vene.tian.base.IProgressView;
import com.vene.tian.base.IView;
import com.vene.tian.data.CPResult;
import com.vene.tian.data.NoticeResult;
import com.vene.tian.data.PersonBalanceResult;
import com.vene.tian.data.PersonInformResult;
import com.vene.tian.data.QipaiResult;

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
    }

}
