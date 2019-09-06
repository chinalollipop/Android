package com.gmcp.gm.ui.me.link;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.RegisterLinkListResult;
import com.gmcp.gm.data.RegisterMeResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface RegisterLinkContract {

    interface Presenter extends IPresenter {

        void getFundGroup();
        void getFundList();
        void getFundDelete(String id);
        void getRegisterFundGroup(String is_agent,String prize_group_id, String prize_group_type, String channel, String agent_qqs, String valid_days, String series_prize_group_json);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getFundGroupResult(RegisterMeResult registerMeResult);
        void getFundListResult(RegisterLinkListResult registerLinkListResult);
        void getRegisterFundGroupResult();
        void getFundDeleteResult();
    }
}
