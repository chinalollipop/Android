package com.hfcp.hf.common.adapters;

import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;
import com.hfcp.hf.R;
import com.hfcp.hf.data.GamesTipsResult;

import java.util.List;

public class LotteryTipsAdapter extends BaseQuickAdapter<GamesTipsResult.DataBean, BaseViewHolder> {

    public LotteryTipsAdapter(List<GamesTipsResult.DataBean> data) {
        super(R.layout.item_games_tips, data);
    }

    @Override
    protected void convert(BaseViewHolder helper, GamesTipsResult.DataBean item) {
        helper.setText(R.id.tv_type, "彩种：" + item.getLottery());
        helper.setText(R.id.tv_issue, "期数：" + item.getIssue());
        helper.setText(R.id.tv_bonus, "盈利金额：(" + item.getBonus() + ")");
        if (item.getStatus().equals("2")) {
            helper.setBackgroundColor(R.id.ll_tips, mContext.getResources().getColor(R.color.white));
            helper.setBackgroundColor(R.id.view_tips, mContext.getResources().getColor(R.color.text_bet_noraml));
            helper.setText(R.id.tv_tips, "未中奖");
            helper.setTextColor(R.id.tv_tips, mContext.getResources().getColor(R.color.text_bet_noraml));
            helper.setTextColor(R.id.tv_type, mContext.getResources().getColor(R.color.text_black));
            helper.setTextColor(R.id.tv_issue, mContext.getResources().getColor(R.color.text_black));
            helper.setTextColor(R.id.tv_bonus, mContext.getResources().getColor(R.color.text_black));
        } else {
            helper.setBackgroundColor(R.id.ll_tips, mContext.getResources().getColor(R.color.text_bet_noraml));
            helper.setBackgroundColor(R.id.view_tips, mContext.getResources().getColor(R.color.white));
            helper.setText(R.id.tv_tips, "中奖啦！！！");
            helper.setTextColor(R.id.tv_tips, mContext.getResources().getColor(R.color.white));
            helper.setTextColor(R.id.tv_type, mContext.getResources().getColor(R.color.white));
            helper.setTextColor(R.id.tv_issue, mContext.getResources().getColor(R.color.white));
            helper.setTextColor(R.id.tv_bonus, mContext.getResources().getColor(R.color.white));
        }
    }
}