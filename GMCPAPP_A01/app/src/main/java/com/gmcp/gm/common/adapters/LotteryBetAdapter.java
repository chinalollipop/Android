package com.gmcp.gm.common.adapters;

import android.widget.TextView;

import com.gmcp.gm.R;
import com.gmcp.gm.common.utils.CommentUtils;
import com.gmcp.gm.common.utils.DimensUtils;
import com.gmcp.gm.data.UpBetData;
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

    LotteryBetAdapter(List<String> data, int position, int lotteryID) {
        super(R.layout.item_lottery, data);
        layoutPosition = position;
        mLotteryID = lotteryID;
    }

    @Override
    protected void convert(BaseViewHolder helper, String item) {
        helper.setText(R.id.tv_lottery_num, item);
        TextView tvLotteryNum = helper.getView(R.id.tv_lottery_num);
        if (mLotteryID == 15 || mLotteryID == 17 || mLotteryID == 50 || mLotteryID == 51) {
            tvLotteryNum.setBackgroundResource(R.drawable.selector_lottery_method);
            tvLotteryNum.setWidth(DimensUtils.dipToPx(mContext, 55));
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
