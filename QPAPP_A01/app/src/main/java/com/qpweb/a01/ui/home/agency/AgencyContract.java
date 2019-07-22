package com.qpweb.a01.ui.home.agency;

import com.qpweb.a01.base.IMessageView;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.base.IView;
import com.qpweb.a01.data.DetailListResult;
import com.qpweb.a01.data.DetailWeekListResult;
import com.qpweb.a01.data.MyAgencyResults;
import com.qpweb.a01.data.ProListResults;
import com.qpweb.a01.data.RedPacketResult;

import java.util.List;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface AgencyContract {

    public interface Presenter extends IPresenter {
        public void postMyProList(String appRefer, String action);
        public void postProDetail(String appRefer, String action);
        public void postWeeksDetail(String appRefer, String action);
        public void postGetMyPromotion(String appRefer, String action);
        public void postGetMyPromotionRecord(String appRefer, String action);
    }

    public interface View extends IView<Presenter>, IMessageView {

        public void postMyProListResult(MyAgencyResults myAgencyResults);
        public void postDetailListResult(List<DetailListResult> detailListResult);
        public void postWeeksDetailResult(List<DetailWeekListResult> detailListResult);
        public void postGetMyPromotionRecordResult(List<ProListResults> proListResults);
    }
}
