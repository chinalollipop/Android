package com.gmcp.gm.ui.me.register;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.RegisterMeResult;

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
