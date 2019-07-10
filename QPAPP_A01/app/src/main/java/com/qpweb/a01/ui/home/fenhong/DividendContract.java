package com.qpweb.a01.ui.home.fenhong;

import com.qpweb.a01.base.IMessageView;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.base.IView;
import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.data.TouziResult;
import com.qpweb.a01.data.TouziYestodayResult;

import java.util.List;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface DividendContract {

    public interface Presenter extends IPresenter {
        public void postTouziYestodayList(String appRefer, String type);
        public void postTouziSign(String appRefer, String type);
        public void postTouzi(String appRefer, String type);
        public void postTouziRecord(String appRefer, String type);
    }

    public interface View extends IView<Presenter>, IMessageView {

        public void postTouziYestodayListResult(TouziYestodayResult touziYestodayResult);
        public void postTouziRecordResult(List<TouziResult> touziResult);
        public void postTouziResult();
    }
}
