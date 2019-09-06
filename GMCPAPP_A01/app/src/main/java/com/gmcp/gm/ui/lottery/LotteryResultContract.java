package com.gmcp.gm.ui.lottery;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.CPLotteryListResult;
import com.gmcp.gm.data.LotteryListResult;

import java.util.List;

/**
 * Created by Daniel on 2019/2/20.
 */

public interface LotteryResultContract {

    interface Presenter extends IPresenter {

        void getLotteryList(String terminal_id, String lottery_id, String token);
        void postCPLotteryList(String dateStr,String dataId);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getLotteryListResult(List<LotteryListResult> lotteryListResult);
        void postCPLotteryListResult(CPLotteryListResult cpLotteryListResult);
    }
}
