package com.gmcp.gm.common.widget;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.OvershootInterpolator;
import android.view.animation.TranslateAnimation;
import android.widget.LinearLayout;

import com.gmcp.gm.R;
import com.gmcp.gm.common.adapters.LotteryMethodContentAdapter;
import com.gmcp.gm.common.adapters.LotteryMethodPlayAdapter;
import com.gmcp.gm.common.utils.DimensUtils;
import com.gmcp.gm.data.BetGameSettingsForRefreshResult;
import com.chad.library.adapter.base.BaseQuickAdapter;

import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import razerdp.basepopup.BasePopupWindow;

/**
 * 彩种玩法选择弹窗
 */

public class LotteryPlayMethodPop extends BasePopupWindow {

    @BindView(R.id.rv_content)
    RecyclerView rvContent;
    @BindView(R.id.rv_play)
    RecyclerView rvPlay;
    @BindView(R.id.ll_confirm)
    LinearLayout llConfirm;

    private boolean mConfirm = false;

    private List<BetGameSettingsForRefreshResult.DataBean.WayGroupsBean> mBetWaySettingsResult;
    private int contentPosition = 0;//设置玩法内容位置
    private int contentSelect = 0;//记录玩法内容用户选择位置
    private int playPosition = 0;//设置玩法二级父位置
    private int playSelect = 0;//设置玩法二级子位置

    private LotteryMethodContentAdapter mLotteryContent;
    private LotteryMethodPlayAdapter mLotteryPlay;

    public LotteryPlayMethodPop(final Context context, List<BetGameSettingsForRefreshResult.DataBean.WayGroupsBean> betWaySettingsResult) {
        super(context);
        setAlignBackground(true);
        mBetWaySettingsResult = betWaySettingsResult;
        FlowLayoutManager betContent = new FlowLayoutManager();
        rvContent.addItemDecoration(new SpaceItemDecoration(DimensUtils.dipToPx(context, 3)));
        rvContent.setLayoutManager(betContent);
        mLotteryContent = new LotteryMethodContentAdapter(mBetWaySettingsResult);
        rvContent.setAdapter(mLotteryContent);

        FlowLayoutManager betPlay = new FlowLayoutManager();
        rvPlay.addItemDecoration(new SpaceItemDecoration(DimensUtils.dipToPx(context, 3)));
        rvPlay.setLayoutManager(betPlay);
        mLotteryPlay = new LotteryMethodPlayAdapter();
        rvPlay.setAdapter(mLotteryPlay);
        //设置默认选项
        refresh();
        mLotteryContent.setOnItemClickListener(new BaseQuickAdapter.OnItemClickListener() {
            @Override
            public void onItemClick(BaseQuickAdapter adapter, View view, int position) {
                contentSelect = position;
                mLotteryContent.setSelect(position);
                mLotteryContent.notifyDataSetChanged();
                mLotteryPlay.setSelect(0, 0);
                mLotteryPlay.setNewData(mBetWaySettingsResult.get(position).getChildren());
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
        View view = createPopupById(R.layout.pop_lottery_play_method);
        ButterKnife.bind(this, view);
        return view;
    }

    @OnClick(R.id.ll_confirm)
    void onClick() {
        String[] position = mLotteryPlay.getSelect().split(",");
        if (contentPosition != contentSelect || playPosition != Integer.valueOf(position[0]) || playSelect != Integer.valueOf(position[1])) {
            contentPosition = contentSelect;
            playPosition = Integer.valueOf(position[0]);
            playSelect = Integer.valueOf(position[1]);
            mConfirm = true;
        }
        dismiss();
    }

    public void refresh() {
        //设置默认选项
        mLotteryContent.setSelect(contentPosition);
        mLotteryContent.notifyDataSetChanged();
        mLotteryPlay.setSelect(playPosition, playSelect);
        mLotteryPlay.setNewData(mBetWaySettingsResult.get(contentPosition).getChildren());
    }

    //获取选项
    public String getPosition() {
        return contentPosition + "," + playPosition + "," + playSelect;
    }

    //设置是否确定
    public void setConfirm(boolean confirm) {
        mConfirm = confirm;
    }

    //获取是否确定
    public boolean getConfirm() {
        return mConfirm;
    }

    //设置默认选项
    public void setDefault(String[] position) {
        contentPosition = Integer.valueOf(position[0]);
        playPosition = Integer.valueOf(position[1]);
        playSelect = Integer.valueOf(position[2]);
        mLotteryContent.setSelect(contentPosition);
        mLotteryContent.notifyDataSetChanged();
        mLotteryPlay.setSelect(playPosition, playSelect);
        mLotteryPlay.setNewData(mBetWaySettingsResult.get(contentPosition).getChildren());
    }
}
