package com.nhg.xhg.personpage;

import com.nhg.xhg.base.IMessageView;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.base.IProgressView;
import com.nhg.xhg.base.IView;
import com.nhg.xhg.data.AGGameLoginResult;
import com.nhg.xhg.data.CPResult;
import com.nhg.xhg.data.NoticeResult;
import com.nhg.xhg.data.PersonBalanceResult;
import com.nhg.xhg.data.PersonInformResult;
import com.nhg.xhg.data.QipaiResult;

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
