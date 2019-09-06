package com.gmcp.gm.ui.home.withdraw;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.WithDrawNextResult;
import com.gmcp.gm.data.WithDrawResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface WithDrawContract {

    interface Presenter extends IPresenter {

        void getWithDraw();
        void getWithDrawNext(String id, String amount);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getWithDrawResult(WithDrawResult withDrawResult);
        void getAddCard();
        void getWithDrawNextResult(WithDrawNextResult withDrawNextResult);
    }
}
