package com.gmcp.gm.common.adapters;

import android.widget.TextView;

import com.gmcp.gm.R;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import java.util.List;

public class LotteryOptionAdapter extends BaseQuickAdapter<String, BaseViewHolder> {

    private int layoutPosition;
    private int selectPosition;
    private int isSelect;

    LotteryOptionAdapter(List<String> data, int position) {
        super(R.layout.item_lottery_option, data);
        layoutPosition = position;
    }

    @Override
    protected void convert(BaseViewHolder helper, String item) {
        helper.setText(R.id.tv_option, item);
        TextView tvOption = helper.getView(R.id.tv_option);
        if (layoutPosition == selectPosition && isSelect == helper.getLayoutPosition()) {
            tvOption.setSelected(true);
        }
    }

    void setSelect(int position, int select) {
        selectPosition = position;
        isSelect = select;
    }
}
