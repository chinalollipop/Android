package com.hfcp.hf.ui.me.record.overbet;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.TraceListResult;

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
