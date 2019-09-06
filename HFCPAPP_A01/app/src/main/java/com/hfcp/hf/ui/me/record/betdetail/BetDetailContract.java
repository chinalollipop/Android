package com.hfcp.hf.ui.me.record.betdetail;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.BetDetailResult;

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
