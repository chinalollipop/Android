package com.gmcp.gm.ui.me.record.overbet;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.TraceListResult;

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
