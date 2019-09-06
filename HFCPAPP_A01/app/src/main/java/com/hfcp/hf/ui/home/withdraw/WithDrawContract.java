package com.hfcp.hf.ui.home.withdraw;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.WithDrawNextResult;
import com.hfcp.hf.data.WithDrawResult;

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
