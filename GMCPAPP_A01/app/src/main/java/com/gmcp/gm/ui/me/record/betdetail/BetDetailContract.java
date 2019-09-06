package com.gmcp.gm.ui.me.record.betdetail;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.BetDetailResult;

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
