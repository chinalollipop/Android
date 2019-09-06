package com.gmcp.gm.common.adapters;

import android.widget.TextView;

import com.gmcp.gm.R;
import com.gmcp.gm.data.BetGameSettingsForRefreshResult;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

public class LotteryMethodPlayDetailAdapter extends BaseQuickAdapter<BetGameSettingsForRefreshResult.DataBean.WayGroupsBean.ChildrenBeanX.ChildrenBean, BaseViewHolder> {
    private int layoutPosition;
    private int selectPosition;
    private int isSelect;
    LotteryMethodPlayDetailAdapter(int position) {
        super(R.layout.item_po_detail);
        layoutPosition = position;
    }

    @Override
    protected void convert(final BaseViewHolder helper, final BetGameSettingsForRefreshResult.DataBean.WayGroupsBean.ChildrenBeanX.ChildrenBean item) {
        TextView textView = helper.getView(R.id.tv_item_detail);
        textView.setText(item.getName_cn());
        if (layoutPosition == selectPosition && isSelect == helper.getLayoutPosition()) {
            textView.setSelected(true);
        }
    }

    void setSelect(int position, int select) {
        selectPosition = position;
        isSelect = select;
    }
}
