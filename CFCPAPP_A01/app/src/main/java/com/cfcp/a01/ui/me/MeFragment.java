package com.cfcp.a01.ui.me;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.cfcp.a01.R;
import com.cfcp.a01.base.BaseFragment;
import com.cfcp.a01.common.adapters.AutoSizeRVAdapter;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.ui.home.HomeFragment;
import com.cfcp.a01.ui.home.HomeIconEvent;
import com.cfcp.a01.ui.home.enumeration.LotteryType;
import com.cfcp.a01.utils.GameLog;
import com.cfcp.a01.utils.NetworkUtils;
import com.cfcp.a01.widget.GridRvItemDecoration;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class MeFragment extends BaseFragment {


    @BindView(R.id.meUser)
    TextView meUser;
    @BindView(R.id.meRegister)
    TextView meRegister;
    @BindView(R.id.meLogout)
    TextView meLogout;
    @BindView(R.id.meDeposit)
    LinearLayout meDeposit;
    @BindView(R.id.meWithDraw)
    LinearLayout meWithDraw;
    @BindView(R.id.meBottom)
    LinearLayout meBottom;
    @BindView(R.id.meRecyView)
    RecyclerView meRecyView;
    private static List<HomeIconEvent> meCenterList = new ArrayList<HomeIconEvent>();
    static {
        meCenterList.add(new HomeIconEvent("游戏记录","每分钟一期",R.mipmap.me_game_records,LotteryType.TYPE_5FC,5));
        meCenterList.add(new HomeIconEvent("追号查询","每分钟一期",R.mipmap.me_zhuihao_records,LotteryType.TYPE_JSSC,5));
        meCenterList.add(new HomeIconEvent("个人报表","每分钟一期",R.mipmap.me_personal_table,LotteryType.TYPE_CQSSC,5));
        meCenterList.add(new HomeIconEvent("团队报表","每分钟一期",R.mipmap.me_team_table,LotteryType.TYPE_BJPK10,5));
        meCenterList.add(new HomeIconEvent("账变报表","每分钟一期",R.mipmap.me_account_change,LotteryType.TYPE_3FC,5));
        meCenterList.add(new HomeIconEvent("充值记录","每分钟一期",R.mipmap.me_deposit_records,LotteryType.TYPE_1FC,5));
        meCenterList.add(new HomeIconEvent("优惠活动","每分钟一期",R.mipmap.me_discounts_activity,LotteryType.TYPE_11X5,5));
        meCenterList.add(new HomeIconEvent("用户资料","每分钟一期",R.mipmap.me_use_infor,LotteryType.TYPE_JSK3,5));
        meCenterList.add(new HomeIconEvent("银行卡","每分钟一期",R.mipmap.me_bank_card,LotteryType.TYPE_11X5_GD,5));
        meCenterList.add(new HomeIconEvent("个人总览","每分钟一期",R.mipmap.me_personal_overview,LotteryType.TYPE_K3FFC,5));
        meCenterList.add(new HomeIconEvent("密码设定","每分钟一期",R.mipmap.me_pwd_set,LotteryType.TYPE_JS3D,5));
        meCenterList.add(new HomeIconEvent("密码修改","每分钟一期",R.mipmap.me_pwd_change,LotteryType.TYPE_BJKL8,5));
        meCenterList.add(new HomeIconEvent("彩种信息","每分钟一期",R.mipmap.me_lottery_infor,LotteryType.TYPE_11X5_3FC,5));

        meCenterList.add(new HomeIconEvent("彩种限额","每分钟一期",R.mipmap.me_lottery_limit,LotteryType.TYPE_JS3D,5));
        meCenterList.add(new HomeIconEvent("开奖结果","每分钟一期",R.mipmap.me_lottery_end,LotteryType.TYPE_BJKL8,5));
        meCenterList.add(new HomeIconEvent("走势图","每分钟一期",R.mipmap.me_run_chart,LotteryType.TYPE_11X5_3FC,5));

        meCenterList.add(new HomeIconEvent("团队总览","每分钟一期",R.mipmap.me_team_overview,LotteryType.TYPE_JS3D,5));
        meCenterList.add(new HomeIconEvent("用户列表","每分钟一期",R.mipmap.me_use_list,LotteryType.TYPE_BJKL8,5));
        meCenterList.add(new HomeIconEvent("推广链接","每分钟一期",R.mipmap.me_seo_link,LotteryType.TYPE_11X5_3FC,5));

        meCenterList.add(new HomeIconEvent("站内短信","每分钟一期",R.mipmap.me_instation_infor,LotteryType.TYPE_BJKL8,5));
        meCenterList.add(new HomeIconEvent("网站公告","每分钟一期",R.mipmap.me_website_notice,LotteryType.TYPE_11X5_3FC,5));
    }

    public static MeFragment newInstance() {
        MeFragment MeFragment = new MeFragment();

        return MeFragment;
    }


    @Override
    public int setLayoutId() {
        return R.layout.fragment_me;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(),3, OrientationHelper.VERTICAL,false);
        meRecyView.setLayoutManager(gridLayoutManager);
        meRecyView.setHasFixedSize(true);
        meRecyView.setNestedScrollingEnabled(false);
        meRecyView.addItemDecoration(new GridRvItemDecoration(getContext()));
        meRecyView.setAdapter(new MeAdapter(getContext(),R.layout.item_me,meCenterList));
    }

    class MeAdapter extends AutoSizeRVAdapter<HomeIconEvent> {

        public MeAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
        }

        @Override
        protected void convert(ViewHolder holder, HomeIconEvent data, final int position) {
            /*TextView textView = holder.getView(R.id.itemHomeIconName);
            if(position==8){
                textView.setTextColor(getResources().getColor(R.color.event_red));
            }else{
                textView.setTextColor(getResources().getColor(R.color.login_left));
            }*/
            holder.setText(R.id.itemMeIconName,data.getIconName());
            holder.setBackgroundRes(R.id.itemMeIconDrawable,data.getIconDrawable());
            holder.setOnClickListener(R.id.itemMeShow, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if(!NetworkUtils.isConnected()){
                        showMessage("请检查您的网络！");
                        return;
                    }
                }
            });
        }
    }


    @Subscribe
    public void onEventMain(LoginResult loginResult) {
        GameLog.log("================注册页需要消失的================");
        finish();
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().unregister(this);
    }

    @Override
    public void onSupportVisible() {
        super.onSupportVisible();
        showMessage("个人信息界面");
        //EventBus.getDefault().post(new MainEvent(0));
    }

    @OnClick({R.id.meUser, R.id.meRegister, R.id.meLogout, R.id.meDeposit, R.id.meWithDraw})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.meUser:
                break;
            case R.id.meRegister:
                break;
            case R.id.meLogout:
                break;
            case R.id.meDeposit:
                break;
            case R.id.meWithDraw:
                break;
        }
    }
}
