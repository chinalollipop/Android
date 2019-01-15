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
import com.cfcp.a01.data.LogoutResult;
import com.cfcp.a01.ui.main.MainEvent;
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
//用户中心
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
    private static List<MeIconEvent> meCenterList = new ArrayList<MeIconEvent>();
    public enum MemberType {
        ME_GAME_RECORDS,        //游戏记录
        ME_ZHUIHAO_RECORDS,     //追号查询
        ME_PERSONAL_TABLE,      //个人报表
        ME_TEAM_TABLE,          //团队报表
        ME_ACCOUNT_CHANGE,      //账变报表
        ME_DEPOSIT_RECORDS,     //充值记录
        ME_DISCOUNTS_ACTIVITY,  //优惠活动
        ME_USE_INFOR,           //用户资料
        ME_BANK_CARD,           //银行卡
        ME_PERSONAL_OVERVIEW,   //个人总览
        ME_PWD_SET,             //密码设定
        ME_PWD_CHANGE,          //密码修改
        ME_LOTTERY_INFOR,       //彩种信息
        ME_LOTTERY_LIMIT,       //彩种限额
        ME_LOTTERY_END,         //开奖结果
        ME_RUN_CHART,           //走势图
        ME_TEAM_OVERVIEW,       //团队总览
        ME_USE_LIST,            //用户列表
        ME_SEO_LINK,            //推广链接
        ME_INSTATION_INFOR,     //站内短信
        ME_WEBSITE_NOTICE       //网站公告
    }
    static {
        meCenterList.add(new MeIconEvent("游戏记录","每分钟一期",R.mipmap.me_game_records,MemberType.ME_GAME_RECORDS,1));
        meCenterList.add(new MeIconEvent("追号查询","每分钟一期",R.mipmap.me_zhuihao_records,MemberType.ME_ZHUIHAO_RECORDS,2));
        meCenterList.add(new MeIconEvent("个人报表","每分钟一期",R.mipmap.me_personal_table,MemberType.ME_PERSONAL_TABLE,3));
        meCenterList.add(new MeIconEvent("团队报表","每分钟一期",R.mipmap.me_team_table,MemberType.ME_TEAM_TABLE,4));
        meCenterList.add(new MeIconEvent("账变报表","每分钟一期",R.mipmap.me_account_change,MemberType.ME_ACCOUNT_CHANGE,5));
        meCenterList.add(new MeIconEvent("充值记录","每分钟一期",R.mipmap.me_deposit_records,MemberType.ME_DEPOSIT_RECORDS,6));
        meCenterList.add(new MeIconEvent("优惠活动","每分钟一期",R.mipmap.me_discounts_activity,MemberType.ME_DISCOUNTS_ACTIVITY,7));
        meCenterList.add(new MeIconEvent("用户资料","每分钟一期",R.mipmap.me_use_infor,MemberType.ME_USE_INFOR,8));
        meCenterList.add(new MeIconEvent("银行卡","每分钟一期",R.mipmap.me_bank_card,MemberType.ME_BANK_CARD,9));
        meCenterList.add(new MeIconEvent("个人总览","每分钟一期",R.mipmap.me_personal_overview,MemberType.ME_PERSONAL_OVERVIEW,10));
        meCenterList.add(new MeIconEvent("密码设定","每分钟一期",R.mipmap.me_pwd_set,MemberType.ME_PWD_SET,11));
        meCenterList.add(new MeIconEvent("密码修改","每分钟一期",R.mipmap.me_pwd_change,MemberType.ME_PWD_CHANGE,12));
        meCenterList.add(new MeIconEvent("彩种信息","每分钟一期",R.mipmap.me_lottery_infor,MemberType.ME_LOTTERY_INFOR,13));
        meCenterList.add(new MeIconEvent("彩种限额","每分钟一期",R.mipmap.me_lottery_limit,MemberType.ME_LOTTERY_INFOR,14));
        meCenterList.add(new MeIconEvent("开奖结果","每分钟一期",R.mipmap.me_lottery_end,MemberType.ME_LOTTERY_END,15));
        meCenterList.add(new MeIconEvent("走势图","每分钟一期",R.mipmap.me_run_chart,MemberType.ME_RUN_CHART,16));
        meCenterList.add(new MeIconEvent("团队总览","每分钟一期",R.mipmap.me_team_overview,MemberType.ME_TEAM_OVERVIEW,17));
        meCenterList.add(new MeIconEvent("用户列表","每分钟一期",R.mipmap.me_use_list,MemberType.ME_USE_LIST,18));
        meCenterList.add(new MeIconEvent("推广链接","每分钟一期",R.mipmap.me_seo_link,MemberType.ME_SEO_LINK,19));
        meCenterList.add(new MeIconEvent("站内短信","每分钟一期",R.mipmap.me_instation_infor,MemberType.ME_INSTATION_INFOR,20));
        meCenterList.add(new MeIconEvent("网站公告","每分钟一期",R.mipmap.me_website_notice,MemberType.ME_WEBSITE_NOTICE,21));
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

    class MeAdapter extends AutoSizeRVAdapter<MeIconEvent> {

        public MeAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
        }

        @Override
        protected void convert(ViewHolder holder,final MeIconEvent data, final int position) {
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
                    switch (data.getIconId()){
                        case ME_GAME_RECORDS:
                            showMessage("游戏记录");
                            break;
                        case ME_ZHUIHAO_RECORDS:
                            showMessage("追号查询");
                            break;
                        case ME_PERSONAL_TABLE:

                            break;
                        case ME_TEAM_TABLE:

                            break;
                        case ME_ACCOUNT_CHANGE:

                            break;
                        case ME_DEPOSIT_RECORDS:

                            break;
                        case ME_DISCOUNTS_ACTIVITY:
                            EventBus.getDefault().post(new MainEvent(2));
                            break;
                        case ME_USE_INFOR:

                            break;
                        case ME_BANK_CARD:

                            break;
                        case ME_PERSONAL_OVERVIEW:

                            break;
                        case ME_PWD_SET:

                            break;
                        case ME_PWD_CHANGE:

                            break;
                        case ME_LOTTERY_INFOR:

                            break;
                        case ME_LOTTERY_LIMIT:

                            break;
                        case ME_LOTTERY_END:

                            break;
                        case ME_RUN_CHART:

                            break;
                        case ME_TEAM_OVERVIEW:

                            break;
                        case ME_USE_LIST:

                            break;
                        case ME_SEO_LINK:

                            break;
                        case ME_INSTATION_INFOR:

                            break;
                        case ME_WEBSITE_NOTICE:

                            break;
                        default:
                                break;
                    }
                }
            });
        }
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

    @Subscribe
    public void onEventMain(LogoutResult logoutResult) {
        GameLog.log("================用户退出了================");
    }

}
