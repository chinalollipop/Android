package com.hfcp.hf.common.widget;

import android.content.Context;
import android.os.CountDownTimer;
import android.support.v7.widget.CardView;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.view.ViewGroup;
import android.widget.RelativeLayout;


import com.hfcp.hf.R;
import com.hfcp.hf.common.adapters.LotteryTipsAdapter;
import com.hfcp.hf.common.utils.DimensUtils;
import com.hfcp.hf.data.GamesTipsResult;

import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import razerdp.basepopup.BasePopupWindow;

/**
 * 中奖提示弹窗
 */

public class LotteryTipsPop extends BasePopupWindow {

    @BindView(R.id.rv_content)
    RecyclerView rvContent;
    @BindView(R.id.cv_content)
    CardView cvContent;

    public LotteryTipsPop(Context context, List<GamesTipsResult.DataBean> lotteriesTipsList) {
        super(context);
        setBackground(R.color.transparent);

        RelativeLayout.LayoutParams layoutParams = (RelativeLayout.LayoutParams) cvContent.getLayoutParams();
        layoutParams.width = DimensUtils.dipToPx(context, 180);
        if (lotteriesTipsList.size() <= 1) {
            layoutParams.height = ViewGroup.LayoutParams.WRAP_CONTENT;
        } else {
            layoutParams.height = DimensUtils.dipToPx(context, 210);
        }
        cvContent.setLayoutParams(layoutParams);

        rvContent.setLayoutManager(new LinearLayoutManager(context));
        final LotteryTipsAdapter lotteryTipsAdapter = new LotteryTipsAdapter(lotteriesTipsList);
        rvContent.setAdapter(lotteryTipsAdapter);
        new CountDownTimer(10 * 1000, 1000) {
            @Override
            public void onTick(long millisUntilFinished) {
            }

            @Override
            public void onFinish() {
                dismiss();
            }
        }.start();
    }

    @Override
    public View onCreateContentView() {
        View view = createPopupById(R.layout.pop_bet_tips);
        ButterKnife.bind(this, view);
        return view;
    }
}