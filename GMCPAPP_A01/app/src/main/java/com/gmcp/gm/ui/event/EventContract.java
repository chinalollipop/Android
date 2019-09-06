package com.gmcp.gm.ui.event;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.CouponResult;

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
