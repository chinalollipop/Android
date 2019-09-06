package com.gmcp.gm.common.widget;

import android.content.Context;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.OvershootInterpolator;
import android.view.animation.TranslateAnimation;

import com.gmcp.gm.R;
import com.gmcp.gm.common.adapters.LotteryTypeAdapter;
import com.gmcp.gm.common.utils.DimensUtils;
import com.gmcp.gm.data.AllGamesResult;
import com.chad.library.adapter.base.BaseQuickAdapter;

import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import razerdp.basepopup.BasePopupWindow;

/**
 * 彩票名称标题弹窗
 */

public class LotteryTypePop extends BasePopupWindow {

    @BindView(R.id.rv_content)
    RecyclerView rvContent;
    private int lotteryPosition = -1;

    public LotteryTypePop(Context context, List<AllGamesResult.DataBean.LotteriesBean> lotteriesBeanList, String title) {
        super(context);
        setAlignBackground(true);
        rvContent.setLayoutManager(new GridLayoutManager(context, 3));
        rvContent.addItemDecoration(new GridRvItemDecoration(getContext()));
        final LotteryTypeAdapter mLotteryTypeAdapter = new LotteryTypeAdapter(lotteriesBeanList);
        rvContent.setAdapter(mLotteryTypeAdapter);
        for (int i = 0; i < lotteriesBeanList.size(); i++) {
            if (lotteriesBeanList.get(i).getName().equals(title)) {
                mLotteryTypeAdapter.setSelect(i);
            }
        }
        mLotteryTypeAdapter.setOnItemClickListener(new BaseQuickAdapter.OnItemClickListener() {
            @Override
            public void onItemClick(BaseQuickAdapter adapter, View view, int position) {
                lotteryPosition = position;
                mLotteryTypeAdapter.setSelect(position);
                mLotteryTypeAdapter.notifyDataSetChanged();
                dismiss();
            }
        });
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
        View view = createPopupById(R.layout.pop_bet_type);
        ButterKnife.bind(this, view);
        return view;
    }

    public int getPosition() {
        return lotteryPosition;
    }
}
