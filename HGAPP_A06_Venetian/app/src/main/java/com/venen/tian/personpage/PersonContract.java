package com.venen.tian.personpage;

import com.venen.tian.base.IMessageView;
import com.venen.tian.base.IPresenter;
import com.venen.tian.base.IProgressView;
import com.venen.tian.base.IView;
import com.venen.tian.data.AGGameLoginResult;
import com.venen.tian.data.CPResult;
import com.venen.tian.data.NoticeResult;
import com.venen.tian.data.PersonBalanceResult;
import com.venen.tian.data.PersonInformResult;
import com.venen.tian.data.QipaiResult;

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
