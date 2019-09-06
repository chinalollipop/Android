package com.gmcp.gm.ui.me.record.tracedetail;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.TraceDetailResult;

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
