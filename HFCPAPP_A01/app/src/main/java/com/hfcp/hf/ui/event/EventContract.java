package com.hfcp.hf.ui.event;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.CouponResult;

/**
 * Created by Daniel on 2012/2/22.
 */

public interface EventContract {

    interface Presenter extends IPresenter {

        void getCoupon(String appRefer, String packet, String action);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getCouponResult(CouponResult couponResult);
    }
}
