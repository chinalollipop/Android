package com.hfcp.hf.ui.me.userlist;

import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.TextView;

import com.hfcp.hf.Injections;
import com.hfcp.hf.R;
import com.hfcp.hf.common.base.BaseFragment;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.event.StartBrotherEvent;
import com.hfcp.hf.common.utils.GameLog;
import com.hfcp.hf.common.utils.GameShipHelper;
import com.hfcp.hf.common.widget.NTitleBar;
import com.hfcp.hf.data.UserListResult;
import com.hfcp.hf.ui.me.info.InfoFragment;
import com.hfcp.hf.ui.me.report.TeamFragment;
import com.hfcp.hf.ui.me.report.myreport.MyReportFragment;
import com.hfcp.hf.ui.me.userlist.setprize.SetPrizeFragment;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.List;

import butterknife.BindView;

public class UserListFragment extends BaseFragment implements UserListContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    private String typeArgs2, typeArgs3;
    UserListContract.Presenter presenter;
    @BindView(R.id.userListRViewBack)
    NTitleBar userListRViewBack;
    @BindView(R.id.userListRView)
    RecyclerView userListRView;
    String startTime, endTime;
    List<UserListResult> userListResults = new ArrayList<>();

    public static UserListFragment newInstance(String deposit_mode, String money) {
        UserListFragment betFragment = new UserListFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, deposit_mode);
        args.putString(TYPE3, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_user_list;
    }

    //标记为红色
    private String onMarkRed(String sign) {
        return " <font color='#e13f51'>" + sign + "</font>";
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            typeArgs2 = getArguments().getString(TYPE2);
            typeArgs3 = getArguments().getString(TYPE3);
        }
    }


    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
        return format.format(date);
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        userListRView.setLayoutManager(linearLayoutManager);
        userListRViewBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
    }


    //请求数据接口
    private void onRequsetData() {
        presenter.getUserList("", endTime);
    }


    @Override
    public void getUserListResult(List<UserListResult> userListResult) {
        GameLog.log("用户列表 成功");
        userListResults = userListResult;
        PersonReportAdapter personReportAdapter = new PersonReportAdapter(R.layout.item_user_list, userListResults);

        if(userListResults.size()==0){
            View view = LayoutInflater.from(getContext()).inflate(R.layout.item_card_nodata, null);
            TextView textView = view.findViewById(R.id.itemNoDate);
            textView.setText("当前查询条件下暂无查询数据");
            textView.setTextColor(Color.parseColor("#C52133"));
            //personReportAdapter.setEmptyView(view);
            personReportAdapter.addHeaderView(view);
        }
        personReportAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
            @Override
            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                switch (view.getId()){
                    case R.id.itemUserListDetail:
                        if (userListResults.get(position).isChecked()) {
                            userListResults.get(position).setChecked(false);
                        } else {
                            userListResults.get(position).setChecked(true);
                        }
                        adapter.notifyDataSetChanged();
                        break;
                    case R.id.itemUserListChange:
                        EventBus.getDefault().post(new StartBrotherEvent(
                                MyReportFragment.newInstance("1",userListResults.get(position).getId()+"")));
                        break;
                    case R.id.itemUserListLowerDetail:
                        EventBus.getDefault().post(new StartBrotherEvent(
                                InfoFragment.newInstance(userListResults.get(position).getId()+"","")));
                        break;
                    case R.id.itemUserListTeamDetail:
                        EventBus.getDefault().post(new StartBrotherEvent(TeamFragment.newInstance(userListResults.get(position).getId()+"","")));
                        break;
                    case R.id.itemUserListFanSet:
                        EventBus.getDefault().post(new StartBrotherEvent(SetPrizeFragment.newInstance(userListResults.get(position).getId()+"","")));
                        break;
                }

            }
        });
        userListRView.setAdapter(personReportAdapter);
    }


    class PersonReportAdapter extends BaseQuickAdapter<UserListResult, BaseViewHolder> {

        public PersonReportAdapter(int layoutResId, @Nullable List<UserListResult> data) {
            super(layoutResId, data);
        }

        @Override
        protected void convert(BaseViewHolder helper, UserListResult item) {
            if (item.isChecked()) {
                helper.setVisible(R.id.itemUserListDetailLay, true);
            } else {
                helper.setGone(R.id.itemUserListDetailLay, false);
            }
            if(item.getChildren_num()>0){//#337ab7
                helper.setTextColor(R.id.itemUserListUserName,Color.parseColor("#337ab7"));
            }else{
                helper.setTextColor(R.id.itemUserListUserName,Color.parseColor("#3b3b3b"));
            }
            helper.setText(R.id.itemUserListUserName, item.getUsername()).
                    setText(R.id.itemUserListAvailable, GameShipHelper.formatMoney(item.getAvailable())).
                    setText(R.id.itemUserListPrize, item.getPrize_group()).
                    setText(R.id.itemUserListUserType,item.getUser_type_formatted()).
                    addOnClickListener(R.id.itemUserListDetail).
                    addOnClickListener(R.id.itemUserListLowerDetail).
                    addOnClickListener(R.id.itemUserListTeamDetail).
                    addOnClickListener(R.id.itemUserListFanSet).
                    addOnClickListener(R.id.itemUserListChange);
        }
    }

    @Override
    public void setPresenter(UserListContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void onSupportVisible() {
        super.onSupportVisible();
        onRequsetData();
    }

}
