package com.hfcp.hf.common.adapters;

import android.widget.TextView;

import com.hfcp.hf.R;
import com.hfcp.hf.data.BetGameSettingsForRefreshResult;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import java.util.List;

public class LotteryMethodContentAdapter extends BaseQuickAdapter<BetGameSettingsForRefreshResult.DataBean.WayGroupsBean, BaseViewHolder> {

    private int isSelect;

    public LotteryMethodContentAdapter(List<BetGameSettingsForRefreshResult.DataBean.WayGroupsBean> data) {
        super(R.layout.item_pop_content, data);
    }

    @Override
    protected void convert(BaseViewHolder helper, BetGameSettingsForRefreshResult.DataBean.WayGroupsBean item) {
        helper.setText(R.id.tv_item, item.getName_cn());
        TextView tvItem = helper.getView(R.id.tv_item);
        if (isSelect == helper.getLayoutPosition()) {
            tvItem.setSelected(true);
        } else {
            tvItem.setSelected(false);
        }
    }

    public void setSelect(int select) {
        isSelect = select;
    }
}
