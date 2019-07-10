package com.qpweb.a01.ui.home.hongbao;

import com.qpweb.a01.base.IMessageView;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.base.IView;
import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.data.ValidResult;

import java.util.List;

/**
 * Created by Daniel on 2017/4/20.
 */

public interface HBaoContract {

    public interface Presenter extends IPresenter {
        public void postValid(String appRefer, String action);
        public void postLuckEnvelope(String appRefer, String action);
        public void postLuckEnvelopeRecord(String appRefer, String action);
    }

    public interface View extends IView<Presenter>, IMessageView {

        public void postValidResult(ValidResult redPacketResult);
        public void postLuckEnvelopeResult(RedPacketResult redPacketResult);
        public void postLuckEnvelopeErrorResult(String message);
        public void postLuckEnvelopeRecordResult(List<ValidResult> redPacketResult);
    }
}
