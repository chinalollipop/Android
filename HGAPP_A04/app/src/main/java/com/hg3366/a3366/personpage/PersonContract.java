package com.hg3366.a3366.personpage;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.CPResult;
import com.hg3366.a3366.data.NoticeResult;
import com.hg3366.a3366.data.PersonBalanceResult;
import com.hg3366.a3366.data.PersonInformResult;
import com.hg3366.a3366.data.QipaiResult;

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
