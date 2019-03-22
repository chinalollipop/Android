package com.cfcp.a01.ui.event;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.CouponResult;

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
