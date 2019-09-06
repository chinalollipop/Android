package com.hfcp.hf.common.widget;

import android.content.Context;
import android.support.v7.widget.DividerItemDecoration;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.OvershootInterpolator;
import android.view.animation.TranslateAnimation;

import com.hfcp.hf.R;
import com.hfcp.hf.common.adapters.LotteryNumAdapter;
import com.hfcp.hf.common.utils.DimensUtils;
import com.hfcp.hf.data.BetGameSettingsForRefreshResult;

import java.util.List;

import razerdp.basepopup.BasePopupWindow;

/**
 * 当前彩种近5期开奖信息
 */

public class LotteryNumPop extends BasePopupWindow {

    public LotteryNumPop(Context context, List<BetGameSettingsForRefreshResult.DataBean.IssueHistoryBean.IssuesBean> betWnNumberList) {
        super(context);
        setAlignBackground(true);
        RecyclerView rvContent = findViewById(R.id.rv_content);
        LinearLayoutManager betNum = new LinearLayoutManager(context);
        rvContent.setLayoutManager(betNum);
        rvContent.addItemDecoration(new DividerItemDecoration(context, DividerItemDecoration.VERTICAL));
        LotteryNumAdapter lotteryNumDeAdapter = new LotteryNumAdapter(betWnNumberList);
        rvContent.setAdapter(lotteryNumDeAdapter);
    }

    @Override
    protected Animation onCreateShowAnimation() {
        TranslateAnimation translateAnimation = new TranslateAnimation(0f, 0f, -DimensUtils.dipToPx(getContext(), 350f), 0);
        translateAnimation.setDuration(450);
        translateAnimation.setInterpolator(new OvershootInterpolator(1));
        return translateAnimation;
    }

    @Override
    protected Animation onCreateDismissAnimation() {
        TranslateAnimation translateAnimation = new TranslateAnimation(0f, 0f, 0, -DimensUtils.dipToPx(getContext(), 350f));
        translateAnimation.setDuration(450);
        translateAnimation.setInterpolator(new OvershootInterpolator(-4));
        return translateAnimation;
    }

    @Override
    public View onCreateContentView() {
        return createPopupById(R.layout.pop_lottery_num);
    }
}
