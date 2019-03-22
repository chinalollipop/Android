package com.cfcp.a01.ui.me.record.overbet;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.TraceListResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface TraceListContract {

    interface Presenter extends IPresenter {
        void getTraceList(String lottery_id,String page,String pagesize,String begin_date,String end_date);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getTraceListResult(TraceListResult traceListResult);
    }
}
