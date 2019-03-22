package com.cfcp.a01.ui.me.record.betdetail;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.BetDetailResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface BetDetailContract {

    interface Presenter extends IPresenter {

        void getProjectDetail(String id);
        void getProjectDrop(String id);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getProjectDetailResult(BetDetailResult betDetailResult);
        void getProjectDropResult(BetDetailResult betDetailResult);
    }
}
