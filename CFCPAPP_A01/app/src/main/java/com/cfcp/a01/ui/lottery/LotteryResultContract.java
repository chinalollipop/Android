package com.cfcp.a01.ui.lottery;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.CPLotteryListResult;
import com.cfcp.a01.data.LotteryListResult;

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
