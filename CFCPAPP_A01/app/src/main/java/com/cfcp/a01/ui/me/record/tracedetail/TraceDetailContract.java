package com.cfcp.a01.ui.me.record.tracedetail;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.TraceDetailResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface TraceDetailContract {

    interface Presenter extends IPresenter {
        void getTraceDetail(String lottery_id);
        void getCancelTraceReserve(String trace_id,String ids);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getTraceDetailResult(TraceDetailResult traceDetailResult);
        void getCancelTraceReserveResult();
    }
}
