package com.cfcp.a01.ui.home.withdraw;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.WithDrawNextResult;
import com.cfcp.a01.data.WithDrawResult;

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
        void getWithDrawNextResult(WithDrawNextResult withDrawNextResult);
    }
}
