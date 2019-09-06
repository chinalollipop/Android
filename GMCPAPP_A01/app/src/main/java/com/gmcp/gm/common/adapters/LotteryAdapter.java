package com.gmcp.gm.common.adapters;

import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.LinearLayout;

import com.gmcp.gm.R;
import com.gmcp.gm.common.utils.CommentUtils;
import com.gmcp.gm.common.widget.FlowLayoutManager;
import com.gmcp.gm.data.LotteryResult;
import com.gmcp.gm.data.UpBetData;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.List;
import java.util.Random;

public class LotteryAdapter extends BaseQuickAdapter<LotteryResult, BaseViewHolder> {

    private int optionLayoutPosition = -1;
    private int optionSelectPosition = -1;
    private int betLayoutPosition = -1;
    private List<Integer> selectList = new ArrayList<>();//记录选择的投注号码位置
    private boolean mPlacesShow = true;//设置位数显隐
    private boolean mSingleSelection = false;//号码选择是否为单选
    private List<UpBetData> updateBetList = new ArrayList<>();
    private List<Integer> listSec = new ArrayList<>();//有FootView时底部re的选择
    private int mSelectKs = 0;
    private int mSelectEleven = 0;
    private int mSelectHappy8 = 0;
    private int listSecSize = 0;//根据任选模式判断底部选择数量

    public LotteryAdapter() {
        super(R.layout.item_lottery_bet);
    }

    @Override
    protected void convert(final BaseViewHolder helper, final LotteryResult item) {
        helper.setText(R.id.tv_places, item.getPlaces());
        //创建选择号码
        if (updateBetList.size() != LotteryAdapter.this.getData().size()) {
            updateBetList.clear();
            for (int i = 0; i < LotteryAdapter.this.getData().size(); i++) {
                UpBetData updateBet = new UpBetData();
                updateBet.setSelectList(new ArrayList<Integer>());
                updateBetList.add(updateBet);
            }
        }
        updateBetList.get(0).setListSec(listSec);
        //设置便捷选择条是否显示
        helper.setGone(R.id.rv_option, mPlacesShow);
        if (item.getOption() == null) {
            helper.setGone(R.id.rv_option, false);
        }
        //设置便捷选择RecyclerView
        RecyclerView rvOption = helper.getView(R.id.rv_option);
        LinearLayoutManager betOption = new LinearLayoutManager(mContext);
        betOption.setOrientation(LinearLayoutManager.HORIZONTAL);
        rvOption.setLayoutManager(betOption);
        LotteryOptionAdapter mLotteryOptionAdapter = new LotteryOptionAdapter(item.getOption(), helper.getLayoutPosition());
        rvOption.setAdapter(mLotteryOptionAdapter);
        //设置数字号码区域RecyclerView
        RecyclerView rvBet = helper.getView(R.id.rv_bet);
        rvBet.setLayoutManager(new FlowLayoutManager());
        final LotteryBetAdapter mLotteryBetAdapter = new LotteryBetAdapter(item.getData(), helper.getLayoutPosition(), mSelectKs);
        rvBet.setAdapter(mLotteryBetAdapter);
        mLotteryOptionAdapter.setSelect(optionLayoutPosition, optionSelectPosition);
        switch (mSelectEleven) {//11选5下的特殊选择模式
            case 113:
            case 114:
            case 115:
            case 116:
            case 117:
            case 118:
            case 119:
            case 120:
            case 121:
                Integer[] lottery = {0, 1};
                mLotteryBetAdapter.setModePosition(Arrays.asList(lottery), updateBetList);
                break;
            default:
                mLotteryBetAdapter.setSelect(betLayoutPosition, updateBetList);//正常选择模式
                break;
        }
        //点击事件
        mLotteryOptionAdapter.setOnItemClickListener(new OnItemClickListener() {
            @Override
            public void onItemClick(BaseQuickAdapter adapter, View view, int position) {
                optionLayoutPosition = helper.getLayoutPosition();
                optionSelectPosition = position;
                betLayoutPosition = helper.getLayoutPosition();
                selectList.clear();
                updateBetList.get(optionLayoutPosition).getSelectList().clear();
                if (mSelectHappy8 != 0) {//北京快乐8的选择模式
                    Random rand = new Random();
                    switch (item.getOption().get(position)) {
                        case "随机":
                            for (int i = 0; i < 10; i++) {
                                selectList.add(rand.nextInt(80));
                            }
                            break;
                        case "大":
                            for (int i = 0; i < 10; i++) {
                                selectList.add(rand.nextInt(40) + 40);
                            }
                            break;
                        case "小":
                            for (int i = 0; i < 10; i++) {
                                selectList.add(rand.nextInt(40));
                            }
                            break;
                        case "单":
                            for (int i = 0; i < 10; i++) {
                                int j = rand.nextInt(80);
                                if (j % 2 != 0) {
                                    j = j - 1;
                                }
                                selectList.add(j);
                            }
                            break;
                        case "双":
                            for (int i = 0; i < 10; i++) {
                                int j = rand.nextInt(80);
                                if (j % 2 == 0) {
                                    j = j + 1;
                                }
                                selectList.add(j);
                            }
                            break;
                        case "清":
                            optionSelectPosition = -1;
                            break;
                    }
                } else {
                    switch (item.getOption().get(position)) {
                        case "全":
                            for (int i = 0; i < item.getData().size(); i++) {
                                selectList.add(i);
                            }
                            break;
                        case "大":
                            for (int i = item.getData().size() - 1; i >= item.getData().size() / 2; i--) {
                                selectList.add(i);
                            }
                            break;
                        case "小":
                            for (int i = 0; i < item.getData().size() / 2; i++) {
                                selectList.add(i);
                            }
                            break;
                        case "单":
                        case "奇":
                            if (Integer.valueOf(item.getData().get(0)) % 2 == 0) {
                                for (int i = 0; i < item.getData().size() / 2; i++) {
                                    selectList.add(i * 2 + 1);
                                }
                            } else {
                                if (item.getData().size() % 2 == 0) {
                                    for (int i = 0; i < item.getData().size() / 2; i++) {
                                        selectList.add(i * 2);
                                    }
                                } else {
                                    for (int i = 0; i < item.getData().size() / 2 + 1; i++) {
                                        selectList.add(i * 2);
                                    }
                                }
                            }
                            break;
                        case "双":
                        case "偶":
                            if (Integer.valueOf(item.getData().get(0)) % 2 != 0) {
                                for (int i = 0; i < item.getData().size() / 2; i++) {
                                    selectList.add(i * 2 + 1);
                                }
                            } else {
                                if (item.getData().size() % 2 == 0) {
                                    for (int i = 0; i < item.getData().size() / 2; i++) {
                                        selectList.add(i * 2);
                                    }
                                } else {
                                    for (int i = 0; i < item.getData().size() / 2 + 1; i++) {
                                        selectList.add(i * 2);
                                    }
                                }
                            }
                            break;
                        case "清":
                            optionSelectPosition = -1;
                            break;
                    }
                }
                for (int i = 0; i < selectList.size(); i++) {
                    updateBetList.get(optionLayoutPosition).getSelectList().add(selectList.get(i));
                }
                if (mSelectEleven != 0) {//11选5特殊选择模式下的删除
                    updateBetList.get(1).getSelectList().removeAll(updateBetList.get(0).getSelectList());
                }
                EventBus.getDefault().post(updateBetList);
                LotteryAdapter.this.notifyItemChanged(optionLayoutPosition);
            }
        });
        mLotteryBetAdapter.setOnItemClickListener(new OnItemClickListener() {
            @Override
            public void onItemClick(BaseQuickAdapter adapter, View view, int position) {
                optionLayoutPosition = helper.getLayoutPosition();
                betLayoutPosition = helper.getLayoutPosition();
                //添加选择的数字
                if (updateBetList.get(betLayoutPosition).getSelectList().contains(position)) {
                    for (int i = 0; i < updateBetList.get(betLayoutPosition).getSelectList().size(); i++) {
                        if (position == updateBetList.get(betLayoutPosition).getSelectList().get(i)) {
                            updateBetList.get(betLayoutPosition).getSelectList().remove(i);
                            i = i - 1;
                        }
                    }
                } else {
                    if (mSingleSelection) {
                        updateBetList.get(betLayoutPosition).getSelectList().clear();
                        updateBetList.get(betLayoutPosition).getSelectList().add(position);
                    } else {
                        updateBetList.get(betLayoutPosition).getSelectList().add(position);
                    }
                }
                //单独设置11选5下的特殊选择模式
                switch (mSelectEleven) {
                    case 113:
                    case 114:
                    case 115:
                    case 116:
                    case 117:
                    case 118:
                    case 119:
                    case 120:
                    case 121:
                        if ((mSelectEleven == 113 || mSelectEleven == 120) && updateBetList.get(0).getSelectList().size() > 1) {
                            updateBetList.get(0).getSelectList().remove(0);
                        }
                        if ((mSelectEleven == 114 || mSelectEleven == 121) && updateBetList.get(0).getSelectList().size() > 2) {
                            updateBetList.get(0).getSelectList().remove(1);
                        }
                        if (mSelectEleven == 115 && updateBetList.get(0).getSelectList().size() > 3) {
                            updateBetList.get(0).getSelectList().remove(2);
                        }
                        if (mSelectEleven == 116 && updateBetList.get(0).getSelectList().size() > 4) {
                            updateBetList.get(0).getSelectList().remove(3);
                        }
                        if (mSelectEleven == 117 && updateBetList.get(0).getSelectList().size() > 5) {
                            updateBetList.get(0).getSelectList().remove(4);
                        }
                        if (mSelectEleven == 118 && updateBetList.get(0).getSelectList().size() > 6) {
                            updateBetList.get(0).getSelectList().remove(5);
                        }
                        if (mSelectEleven == 119 && updateBetList.get(0).getSelectList().size() > 7) {
                            updateBetList.get(0).getSelectList().remove(6);
                        }
                        if (betLayoutPosition == 0) {
                            if (updateBetList.get(1).getSelectList().contains(position)) {
                                for (int i = 0; i < updateBetList.get(1).getSelectList().size(); i++) {
                                    if (position == updateBetList.get(1).getSelectList().get(i)) {
                                        updateBetList.get(1).getSelectList().remove(i);
                                        i = i - 1;
                                    }
                                }
                            }
                        }
                        if (betLayoutPosition == 1) {
                            if (updateBetList.get(0).getSelectList().contains(position)) {
                                for (int i = 0; i < updateBetList.get(0).getSelectList().size(); i++) {
                                    if (position == updateBetList.get(0).getSelectList().get(i)) {
                                        updateBetList.get(0).getSelectList().remove(i);
                                        i = i - 1;
                                    }
                                }
                            }
                        }
                        LotteryAdapter.this.notifyDataSetChanged();
                        break;
                    default:
                        LotteryAdapter.this.notifyItemChanged(betLayoutPosition);
                        break;
                }
                //与上方选择状态联动
                if (mPlacesShow && CommentUtils.isNumeric(mLotteryBetAdapter.getData().get(position))) {
                    //未选择时清空选择状态
                    if (updateBetList.get(betLayoutPosition).getSelectList().size() != 0) {
                        optionSelectPosition = -1;
                    }
                    //全选状态
                    if (updateBetList.get(betLayoutPosition).getSelectList().size() == item.getData().size()) {
                        optionSelectPosition = 0;
                    }
                    //判断选择的数字是否为“大”或者“小”
                    if (updateBetList.get(betLayoutPosition).getSelectList().size() == item.getData().size() / 2 || updateBetList.get(betLayoutPosition).getSelectList().size() == item.getData().size() / 2 + 1) {
                        if (Collections.min(updateBetList.get(betLayoutPosition).getSelectList()) == item.getData().size() / 2) {
                            optionSelectPosition = 1;
                        }
                        if (Collections.max(updateBetList.get(betLayoutPosition).getSelectList()) < item.getData().size() / 2) {
                            optionSelectPosition = 2;
                        }
                    }
                    //判断选择的数字是否为“单、奇”或者“双、偶”
                    if (item.getData().size() % 2 == 0 && updateBetList.get(betLayoutPosition).getSelectList().size() == item.getData().size() / 2) {
                        if (Integer.valueOf(item.getData().get(0)) % 2 == 0) {
                            if (isOdd(updateBetList.get(betLayoutPosition).getSelectList())) {
                                optionSelectPosition = 3;
                            }
                            if (isEven(updateBetList.get(betLayoutPosition).getSelectList())) {
                                optionSelectPosition = 4;
                            }
                        } else {
                            if (isOdd(updateBetList.get(betLayoutPosition).getSelectList())) {
                                optionSelectPosition = 4;
                            }
                            if (isEven(updateBetList.get(betLayoutPosition).getSelectList())) {
                                optionSelectPosition = 3;
                            }
                        }
                    }
                    if (item.getData().size() % 2 != 0 && Integer.valueOf(item.getData().get(0)) % 2 == 0) {
                        if (updateBetList.get(betLayoutPosition).getSelectList().size() == item.getData().size() / 2 && isOdd(updateBetList.get(betLayoutPosition).getSelectList())) {
                            optionSelectPosition = 3;
                        }
                        if (updateBetList.get(betLayoutPosition).getSelectList().size() == item.getData().size() / 2 + 1 && isEven(updateBetList.get(betLayoutPosition).getSelectList())) {
                            optionSelectPosition = 4;
                        }
                    }
                    if (item.getData().size() % 2 != 0 && Integer.valueOf(item.getData().get(0)) % 2 != 0) {
                        if (updateBetList.get(betLayoutPosition).getSelectList().size() == item.getData().size() / 2 && isOdd(updateBetList.get(betLayoutPosition).getSelectList())) {
                            optionSelectPosition = 4;
                        }
                        if (updateBetList.get(betLayoutPosition).getSelectList().size() == item.getData().size() / 2 + 1 && isEven(updateBetList.get(betLayoutPosition).getSelectList())) {
                            optionSelectPosition = 3;
                        }
                    }
                }
                EventBus.getDefault().post(updateBetList);
            }
        });
        //有重庆时时彩FootView时的选择
        LinearLayout bottomLinearLayout = LotteryAdapter.this.getFooterLayout();
        if (bottomLinearLayout != null) {
            RecyclerView rvBottom = bottomLinearLayout.findViewById(R.id.rv_bottom);
            if (rvBottom != null) {
                String[] ops = {"万位", "千位", "百位", "十位", "个位"};
                rvBottom.setLayoutManager(new GridLayoutManager(mContext, 5));
                final LotteryBottomAdapter mLotteryBottomAdapter = new LotteryBottomAdapter(Arrays.asList(ops));
                rvBottom.setAdapter(mLotteryBottomAdapter);
                mLotteryBottomAdapter.setSelect(listSec);
                mLotteryBottomAdapter.setOnItemClickListener(new BaseQuickAdapter.OnItemClickListener() {
                    @Override
                    public void onItemClick(BaseQuickAdapter adapter, View view, int position) {
                        if (listSec.contains(position)) {
                            for (int i = 0; i < listSec.size(); i++) {
                                if (position == listSec.get(i) && listSec.size() > listSecSize) {
                                    listSec.remove(i);
                                    i = i - 1;
                                }
                            }
                        } else {
                            listSec.add(position);
                        }
                        EventBus.getDefault().post(updateBetList);
                        mLotteryBottomAdapter.notifyDataSetChanged();
                    }
                });
            }
        }
    }

    //判断所选数组序号是否为奇数
    private boolean isOdd(List<Integer> list) {
        for (int i = 0; i < list.size(); i++) {
            if (list.get(i) % 2 == 0) {
                return false;
            }
        }
        return true;
    }

    //判断所选数组序号是否为偶数
    private boolean isEven(List<Integer> list) {
        for (int i = 0; i < list.size(); i++) {
            if (list.get(i) % 2 != 0) {
                return false;
            }
        }
        return true;
    }

    //设置选择状态是否显示
    public void setPlacesShow(boolean placesShow) {
        mPlacesShow = placesShow;
    }

    //设置号码选择是否为单选
    public void setSingleSelection(boolean singleSelection) {
        mSingleSelection = singleSelection;
    }

    //清除数字区域选择状态
    public void clearList() {
        for (int i = 0; i < updateBetList.size(); i++) {
            updateBetList.get(i).getSelectList().clear();
        }
        optionSelectPosition = -1;
        EventBus.getDefault().post(updateBetList);
        LotteryAdapter.this.notifyDataSetChanged();
    }

    //设置adapter有FootView时底部的选择状态
    public void setListSec(List<Integer> list) {
        listSec = list;
        listSecSize = list.size();
    }

    //单独设置快三类的样式
    public void setKs(int selectMode) {
        mSelectKs = selectMode;
    }

    //单独设置十一选5的特殊选择模式
    public void setEleven(int selectMode) {
        mSelectEleven = selectMode;
    }

    //单独设置北京快乐8的特殊选择模式
    public void setHappy8(int selectMode) {
        mSelectHappy8 = selectMode;
    }
}
