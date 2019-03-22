package com.cfcp.a01.common.adapters;

import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;

import com.cfcp.a01.R;
import com.cfcp.a01.data.BetGameSettingsForRefreshResult;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

public class LotteryNumAdapter extends BaseQuickAdapter<BetGameSettingsForRefreshResult.DataBean.IssueHistoryBean.IssuesBean, BaseViewHolder> {

    public LotteryNumAdapter(List<BetGameSettingsForRefreshResult.DataBean.IssueHistoryBean.IssuesBean> data) {
        super(R.layout.item_lottery_num, data);
    }

    @Override
    protected void convert(BaseViewHolder helper, BetGameSettingsForRefreshResult.DataBean.IssueHistoryBean.IssuesBean item) {
        helper.setText(R.id.tv_num, item.getIssue() + "期");
        RecyclerView recyclerView = helper.getView(R.id.rv_lottery_num);
        LinearLayoutManager betNum = new LinearLayoutManager(mContext);
        betNum.setOrientation(LinearLayoutManager.HORIZONTAL);//设置 RecyclerView 布局方式为横向布局
        recyclerView.setLayoutManager(betNum);
        String[] num = item.getWn_number().split(",");
        List<String> numList = new ArrayList<>(Arrays.asList(num));
        LotteryNumDetailsAdapter lotteryNumAdapter = new LotteryNumDetailsAdapter(R.layout.item_lottery_num_details, numList);
        recyclerView.setAdapter(lotteryNumAdapter);
    }
}
