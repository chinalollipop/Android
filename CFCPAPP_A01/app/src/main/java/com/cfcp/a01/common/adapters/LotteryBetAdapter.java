package com.cfcp.a01.common.adapters;

import android.widget.TextView;

import com.cfcp.a01.R;
import com.cfcp.a01.common.utils.CommentUtils;
import com.cfcp.a01.common.utils.DimensUtils;
import com.cfcp.a01.data.UpBetData;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import java.util.ArrayList;
import java.util.List;

public class LotteryBetAdapter extends BaseQuickAdapter<String, BaseViewHolder> {

    private int layoutPosition;
    private int selectPosition;
    private List<UpBetData> isSelect = new ArrayList<>();//记录选择的投注号码位置
    private int mLotteryID;
    private List<Integer> modePosition = new ArrayList<>();
    ;

    LotteryBetAdapter(List<String> data, int position, int lotteryID) {
        super(R.layout.item_lottery, data);
        layoutPosition = position;
        mLotteryID = lotteryID;
    }

    @Override
    protected void convert(BaseViewHolder helper, String item) {
        helper.setText(R.id.tv_lottery_num, item);
        TextView tvLotteryNum = helper.getView(R.id.tv_lottery_num);
        if (mLotteryID == 15 || mLotteryID == 17) {
            tvLotteryNum.setBackgroundResource(R.drawable.selector_lottery_method);
            if (getData().size() > 6 && helper.getLayoutPosition() < 4) {
                tvLotteryNum.setWidth(DimensUtils.dipToPx(mContext, 120));
            } else {
                tvLotteryNum.setWidth(DimensUtils.dipToPx(mContext, 55));
            }
            tvLotteryNum.setHeight(120);
        } else if (!CommentUtils.isNumeric(item) && item.length() > 1) {
            tvLotteryNum.setBackgroundResource(R.drawable.selector_lottery_num_oval);
            tvLotteryNum.setTextSize(14);
        }
        if (selectPosition == layoutPosition) {
            for (int i = 0; i < isSelect.get(selectPosition).getSelectList().size(); i++) {
                if (helper.getLayoutPosition() == isSelect.get(selectPosition).getSelectList().get(i)) {
                    tvLotteryNum.setSelected(true);
                }
            }
        }

        for (int i = 0; i < modePosition.size(); i++) {
            if (modePosition.get(i) == layoutPosition) {
                for (int j = 0; j < isSelect.get(modePosition.get(i)).getSelectList().size(); j++) {
                    if (helper.getLayoutPosition() == isSelect.get(modePosition.get(i)).getSelectList().get(j)) {
                        tvLotteryNum.setSelected(true);
                    }
                }
            }
        }
    }

    void setSelect(int position, List<UpBetData> select) {
        selectPosition = position;
        isSelect = select;
    }

    void setModePosition(List<Integer> position, List<UpBetData> select) {
        modePosition = position;
        isSelect = select;
    }
}
