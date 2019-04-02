package com.cfcp.a01.ui.me.register;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.data.RegisterMeResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface RegisterMeContract {

    interface Presenter extends IPresenter {

        void getFundGroup();
        void getRegisterFundGroup(String is_agent,String prize_group_id,String prize_group_type, String nickname, String username, String password,String series_prize_group_json);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getFundGroupResult(RegisterMeResult registerMeResult);
        void getRegisterFundGroupResult();
    }
}
