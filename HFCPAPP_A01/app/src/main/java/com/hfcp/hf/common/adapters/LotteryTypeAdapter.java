package com.hfcp.hf.common.adapters;

import android.widget.TextView;

import com.hfcp.hf.R;
import com.hfcp.hf.data.AllGamesResult;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import java.util.List;

public class LotteryTypeAdapter extends BaseQuickAdapter<AllGamesResult.DataBean.LotteriesBean, BaseViewHolder> {
    private int isSelect = -1;

    public LotteryTypeAdapter(List<AllGamesResult.DataBean.LotteriesBean> data) {
        super(R.layout.item_type_list, data);
    }

    @Override
    protected void convert(BaseViewHolder helper, AllGamesResult.DataBean.LotteriesBean item) {
        helper.setText(R.id.tv_item, item.getName());
        TextView tvItem = helper.getView(R.id.tv_item);
        if (isSelect == helper.getLayoutPosition()) {
            tvItem.setBackgroundResource(R.color.text_bet_submit);
            tvItem.setTextColor(mContext.getResources().getColor(R.color.white));
        } else {
            tvItem.setBackgroundResource(R.color.white);
            tvItem.setTextColor(mContext.getResources().getColor(R.color.text_black));
        }
    }

    public void setSelect(int select) {
        isSelect = select;
    }
}
