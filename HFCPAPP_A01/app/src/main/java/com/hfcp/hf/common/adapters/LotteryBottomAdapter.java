package com.hfcp.hf.common.adapters;

import android.widget.TextView;

import com.hfcp.hf.R;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import java.util.ArrayList;
import java.util.List;

public class LotteryBottomAdapter extends BaseQuickAdapter<String, BaseViewHolder> {

    private List<Integer> isSelect = new ArrayList<>();//记录选择的位置

    public LotteryBottomAdapter(List<String> data) {
        super(R.layout.item_bottom, data);
    }

    @Override
    protected void convert(BaseViewHolder helper, String item) {
        helper.setText(R.id.tv_ops, item);
        TextView tvOps = helper.getView(R.id.tv_ops);
        tvOps.setSelected(false);
        for (int i = 0; i < isSelect.size(); i++) {
            if (helper.getLayoutPosition() == isSelect.get(i)) {
                tvOps.setSelected(true);
            }
        }
    }

    public void setSelect(List<Integer> select) {
        isSelect = select;
    }
}
