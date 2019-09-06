package com.hfcp.hf.ui.me.record.tracedetail;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.TraceDetailResult;

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
