package com.hfcp.hf.ui.me.register;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.RegisterMeResult;

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
