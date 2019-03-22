package com.cfcp.a01.ui.me.report;

import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.TimePickerBuilder;
import com.bigkoo.pickerview.listener.OnTimeSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.bigkoo.pickerview.view.TimePickerView;
import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.utils.DateHelper;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.utils.GameShipHelper;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.PersonReportResult;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import java.text.SimpleDateFormat;
import java.util.Arrays;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class PersonFragment extends BaseFragment implements PersonContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.personTurnover)
    TextView personTurnover;
    @BindView(R.id.personPrize)
    TextView personPrize;
    @BindView(R.id.personProfit)
    TextView personProfit;
    @BindView(R.id.personDetail)
    TextView personDetail;
    @BindView(R.id.personWithDraw)
    TextView personWithDraw;
    @BindView(R.id.personDeposit)
    TextView personDeposit;
    @BindView(R.id.personPrize1)
    TextView personPrize1;
    @BindView(R.id.personBonus)
    TextView personBonus;
    @BindView(R.id.personCom)
    TextView personCom;
    @BindView(R.id.personDetailLay)
    LinearLayout personDetailLay;
    Unbinder unbinder;
    private String typeArgs2, typeArgs3;
    PersonContract.Presenter presenter;
    @BindView(R.id.reportPersonBack)
    NTitleBar reportPersonBack;
    @BindView(R.id.reportPersonStartTime)
    TextView reportPersonStartTime;
    @BindView(R.id.reportPersonEndTime)
    TextView reportPersonEndTime;
    @BindView(R.id.reportPersonRView)
    RecyclerView reportPersonRView;
    TimePickerView pvStartTime;
    TimePickerView pvEndTime;
    OptionsPickerView gtypeOptionsPicker;
    String startTime, endTime;
    List<PersonReportResult.UserProfitsBean> userProfitsBeanList;

    public static PersonFragment newInstance(String deposit_mode, String money) {
        PersonFragment betFragment = new PersonFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, deposit_mode);
        args.putString(TYPE3, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_report_person;
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
        startTime = DateHelper.getToday();
        endTime = DateHelper.getTom();
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        reportPersonRView.setLayoutManager(linearLayoutManager);
        onRequsetData();
        reportPersonStartTime.setText(startTime);
        reportPersonEndTime.setText(endTime);
        reportPersonBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        //开始时间选择器
        pvStartTime = new TimePickerBuilder(getContext(), new OnTimeSelectListener() {
            @Override
            public void onTimeSelect(Date date, View v) {
                startTime = getTime(date);
                onRequsetData();
                reportPersonStartTime.setText(startTime);
            }
        })
                .setType(new boolean[]{true, true, true, false, false, false})// 默认全部显示
                // .setLabel("年","月","日","时","分","秒")//默认设置为年月日时分秒
                .build();
        //结束时间选择器
        pvEndTime = new TimePickerBuilder(getContext(), new OnTimeSelectListener() {
            @Override
            public void onTimeSelect(Date date, View v) {
                endTime = getTime(date);
                onRequsetData();
                reportPersonEndTime.setText(endTime);
            }
        })
                .setType(new boolean[]{true, true, true, false, false, false})// 默认全部显示
                //  .setLabel("年","月","日","时","分","秒")//默认设置为年月日时分秒
                .build();
    }


    //请求数据接口
    private void onRequsetData() {
        presenter.getPersonReport(startTime, endTime);
    }


    @Override
    public void getPersonReportResult(PersonReportResult personReportResult) {
        GameLog.log("个人区间报表 成功");
        userProfitsBeanList = personReportResult.getUser_profits();
        PersonReportResult.SubTotalBean subTotalBean = personReportResult.getSub_total();
        /*personTurnover.setText(GameShipHelper.formatMoney(subTotalBean.getTurnover()));
        personPrize.setText(GameShipHelper.formatMoney(subTotalBean.getPrize()));
        personPrize1.setText(GameShipHelper.formatMoney(subTotalBean.getPrize()));
        personProfit.setText(GameShipHelper.formatMoney(subTotalBean.getProfit()));
        personWithDraw.setText(GameShipHelper.formatMoney(subTotalBean.getWithdrawal()));
        personDeposit.setText(GameShipHelper.formatMoney(subTotalBean.getDeposit()));
        personBonus.setText(GameShipHelper.formatMoney(subTotalBean.getBonus()));
        personCom.setText(GameShipHelper.formatMoney(subTotalBean.getCommission()));*/
        PersonReportResult.UserProfitsBean userProfitsBean = new PersonReportResult.UserProfitsBean();
        userProfitsBean.setDate("总计");
        userProfitsBean.setDeposit(subTotalBean.getDeposit());
        userProfitsBean.setBonus(subTotalBean.getBonus());
        userProfitsBean.setCommission(subTotalBean.getCommission());
        userProfitsBean.setLose_commission(subTotalBean.getCommission());
        userProfitsBean.setWithdrawal(subTotalBean.getWithdrawal());
        userProfitsBean.setPrize(subTotalBean.getPrize());
        userProfitsBean.setProfit(subTotalBean.getProfit());
        userProfitsBean.setTurnover(subTotalBean.getTurnover());
        userProfitsBeanList.add(userProfitsBean);
        PersonReportAdapter personReportAdapter = new PersonReportAdapter(R.layout.item_person_report, userProfitsBeanList);

        if(userProfitsBeanList.size()==1){
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
                if (userProfitsBeanList.get(position).isChecked()) {
                    userProfitsBeanList.get(position).setChecked(false);
                } else {
                    userProfitsBeanList.get(position).setChecked(true);
                }
                adapter.notifyDataSetChanged();
            }
        });
        reportPersonRView.setAdapter(personReportAdapter);
    }


    class PersonReportAdapter extends BaseQuickAdapter<PersonReportResult.UserProfitsBean, BaseViewHolder> {

        public PersonReportAdapter(int layoutResId, @Nullable List<PersonReportResult.UserProfitsBean> data) {
            super(layoutResId, data);
        }

        @Override
        protected void convert(BaseViewHolder helper, PersonReportResult.UserProfitsBean item) {
            if (item.isChecked()) {
                helper.setVisible(R.id.itemPersonDetailLay, true);
            } else {
                helper.setGone(R.id.itemPersonDetailLay, false);
            }
            if(item.getDate().equals("总计")){
                helper.setBackgroundColor(R.id.itemPersonDate,Color.parseColor("#579718"));
                helper.setBackgroundColor(R.id.itemPersonLay,Color.parseColor("#7e7e7e"));
                helper.setTextColor(R.id.itemPersonTurnover,getResources().getColor(R.color.white));
                helper.setTextColor(R.id.itemPersonPrize,getResources().getColor(R.color.white));
                helper.setTextColor(R.id.itemPersonProfit,getResources().getColor(R.color.white));
            }
            helper.setText(R.id.itemPersonDate, item.getDate()).
                    setText(R.id.itemPersonPrize, GameShipHelper.formatMoney(item.getPrize())).
                    setText(R.id.itemPersonPrize1, GameShipHelper.formatMoney(item.getPrize())).
                    setText(R.id.itemPersonProfit, GameShipHelper.formatMoney(item.getProfit())).
                    setText(R.id.itemPersonWithDraw, GameShipHelper.formatMoney(item.getWithdrawal())).
                    setText(R.id.itemPersonDeposit, GameShipHelper.formatMoney(item.getDeposit())).
                    setText(R.id.itemPersonTurnover, GameShipHelper.formatMoney(item.getTurnover())).
                    setText(R.id.itemPersonCom, GameShipHelper.formatMoney(item.getCommission())).
                    setText(R.id.itemPersonBonus, GameShipHelper.formatMoney(item.getBonus())).
                    addOnClickListener(R.id.itemPersonDetail);
        }
    }

    @Override
    public void setPresenter(PersonContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void onSupportVisible() {
        super.onSupportVisible();
    }

    @OnClick({R.id.reportPersonStartTime, R.id.reportPersonEndTime,R.id.personDetail})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.reportPersonStartTime:
                pvStartTime.show();
                break;
            case R.id.reportPersonEndTime:
                pvEndTime.show();
                break;
            case R.id.personDetail:
                if(personDetailLay.isShown()){
                    personDetailLay.setVisibility(View.GONE);
                }else{
                    personDetailLay.setVisibility(View.VISIBLE);
                }
                break;

        }
    }
}
