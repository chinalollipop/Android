package com.gmcp.gm.common.adapters;

import android.support.v7.widget.RecyclerView;
import android.view.View;

import com.gmcp.gm.R;
import com.gmcp.gm.common.widget.FlowLayoutManager;
import com.gmcp.gm.data.BetGameSettingsForRefreshResult;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

public class LotteryMethodPlayAdapter extends BaseQuickAdapter<BetGameSettingsForRefreshResult.DataBean.WayGroupsBean.ChildrenBeanX, BaseViewHolder> {
    private int selectPosition = 0;
    private int isSelect = 0;
    public LotteryMethodPlayAdapter() {
        super(R.layout.item_pop_play);
    }

    @Override
    protected void convert(final BaseViewHolder helper, final BetGameSettingsForRefreshResult.DataBean.WayGroupsBean.ChildrenBeanX item) {
        helper.setText(R.id.tv_play, item.getName_cn());
        RecyclerView recyclerView = helper.getView(R.id.rv_play);
        recyclerView.setLayoutManager(new FlowLayoutManager());
        LotteryMethodPlayDetailAdapter mLotteryMethodPlayDetailAdapter = new LotteryMethodPlayDetailAdapter(helper.getLayoutPosition());
        mLotteryMethodPlayDetailAdapter.replaceData(item.getChildren());
        recyclerView.setAdapter(mLotteryMethodPlayDetailAdapter);
        mLotteryMethodPlayDetailAdapter.setSelect(selectPosition, isSelect);
        mLotteryMethodPlayDetailAdapter.setOnItemClickListener(new OnItemClickListener() {
            @Override
            public void onItemClick(BaseQuickAdapter adapter, View view, int position) {
                selectPosition = helper.getLayoutPosition();
                isSelect = position;
                LotteryMethodPlayAdapter.this.notifyDataSetChanged();
            }
        });
    }

    public void setSelect(int position, int select) {
        selectPosition = position;
        isSelect = select;
    }

    public String getSelect() {
        return selectPosition + "," + isSelect;
    }
}
