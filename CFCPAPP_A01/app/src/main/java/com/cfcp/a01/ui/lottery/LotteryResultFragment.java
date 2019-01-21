package com.cfcp.a01.ui.lottery;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.design.widget.TabLayout;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;

import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

//开奖结果
public class LotteryResultFragment extends BaseFragment {

    @BindView(R.id.lotteryResultType)
    TextView lotteryResultType;
    @BindView(R.id.lotteryResultTab)
    TabLayout lotteryResultTab;
    @BindView(R.id.lotteryResultRView)
    RecyclerView lotteryResultRView;

    //type类型选择器
    OptionsPickerView typeOptionsPicker;
    //代表当前的选项
    private String  type = "0";

    static List<String> typeOptionsList  = new ArrayList<>();
    static {
        typeOptionsList.add("五分彩");
        typeOptionsList.add("极速赛车");
        typeOptionsList.add("重庆时时彩");

        typeOptionsList.add("北京PK10");
        typeOptionsList.add("三分彩");
        typeOptionsList.add("分分彩");
        typeOptionsList.add("11选5");
        typeOptionsList.add("极速快3");
        typeOptionsList.add("广东11选5");
        typeOptionsList.add("快3分分彩");
        typeOptionsList.add("极速3D");
        typeOptionsList.add("北京快乐8");
        typeOptionsList.add("11选5三分彩");

    }

        public static LotteryResultFragment newInstance() {
        LotteryResultFragment MeFragment = new LotteryResultFragment();

        return MeFragment;
    }

    private void initZTTablayout(){
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("开奖"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("冠军走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("亚军走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("季军走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("第四走势"));
        lotteryResultTab.addOnTabSelectedListener(new TabLayout.OnTabSelectedListener() {
            @Override
            public void onTabSelected(TabLayout.Tab tab) {
                switch (tab.getPosition()){
                    case 0:
                        type = "5";
                        break;
                    case 1:
                        type = "6";
                        break;
                    case 2:
                        type = "7";
                        break;
                    case 3:
                        type = "8";
                        break;
                    case 4:
                        type = "9";
                        break;
                    case 5:
                        type = "10";
                        break;
                }

            }

            @Override
            public void onTabUnselected(TabLayout.Tab tab) {

            }

            @Override
            public void onTabReselected(TabLayout.Tab tab) {

            }
        });


        typeOptionsPicker = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                String text = typeOptionsList.get(options1);
                lotteryResultType.setText(text);
                 //1、tab做相应的切换
                // 2、下面做查询数据的请求和展示

            }
        }).build();
        typeOptionsPicker.setPicker(typeOptionsList);

    }



    @Override
    public int setLayoutId() {
        return R.layout.fragment_lottery_result;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
//        EventBus.getDefault().register(this);
        initZTTablayout();
    }


    @Override
    public void onDestroyView() {
        super.onDestroyView();
//        EventBus.getDefault().unregister(this);
    }

    @Override
    public void onSupportVisible() {
        super.onSupportVisible();
        showMessage("开奖结果界面");
        //EventBus.getDefault().post(new MainEvent(0));
    }

    @OnClick(R.id.lotteryResultType)
    public void onViewClicked() {
        typeOptionsPicker.show();

    }
}
