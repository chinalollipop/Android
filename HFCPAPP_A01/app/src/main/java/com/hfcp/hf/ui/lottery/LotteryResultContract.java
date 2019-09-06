package com.hfcp.hf.ui.lottery;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.CPLotteryListResult;
import com.hfcp.hf.data.LotteryListResult;

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
