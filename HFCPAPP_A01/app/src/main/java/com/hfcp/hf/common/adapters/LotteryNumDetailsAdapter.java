package com.hfcp.hf.common.adapters;

import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;
import com.hfcp.hf.R;

import java.util.List;

public class LotteryNumDetailsAdapter extends BaseQuickAdapter<String, BaseViewHolder> {

    public LotteryNumDetailsAdapter(int layoutResId, List<String> data) {
        super(layoutResId, data);
    }

    @Override
    protected void convert(BaseViewHolder helper, String item) {
        helper.setText(R.id.tv_lottery_num, item);
    }
}
