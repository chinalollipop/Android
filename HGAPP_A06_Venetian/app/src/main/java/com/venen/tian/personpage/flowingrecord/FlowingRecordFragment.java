package com.venen.tian.personpage.flowingrecord;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.Button;
import android.widget.ListView;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.TimePickerBuilder;
import com.bigkoo.pickerview.listener.OnTimeSelectListener;
import com.bigkoo.pickerview.view.TimePickerView;
import com.venen.tian.Injections;
import com.venen.tian.R;
import com.venen.tian.base.HGBaseFragment;
import com.venen.tian.base.IPresenter;
import com.venen.tian.common.util.DateHelper;
import com.venen.tian.common.widgets.NTitleBar;
import com.venen.tian.data.FlowingRecordResult;
import com.venen.tian.data.RecordResult;
import com.venen.tian.common.adapters.AutoSizeAdapter;
import com.venen.common.util.GameLog;
import com.zhy.adapter.abslistview.ViewHolder;

import java.text.SimpleDateFormat;
import java.util.Arrays;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class FlowingRecordFragment extends HGBaseFragment implements FlowingRecordContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    @BindView(R.id.tvFlowingRecordBack)
    NTitleBar tvFlowingRecordBack;
    @BindView(R.id.tvFlowingRecordStartTime)
    TextView tvFlowingRecordStartTime;
    @BindView(R.id.tvFlowingRecordEndTime)
    TextView tvFlowingRecordEndTime;
    @BindView(R.id.tvFlowingRecordToday)
    TextView tvFlowingRecordToday;
    @BindView(R.id.tvFlowingRecordLastDay)
    TextView tvFlowingRecordLastDay;
    @BindView(R.id.tvFlowingRecordLastWeek)
    TextView tvFlowingRecordLastWeek;
    @BindView(R.id.tvFlowingRecordLastMonth)
    TextView tvFlowingRecordLastMonth;
    @BindView(R.id.btnFlowingRecordSubmit)
    Button btnFlowingRecordSubmit;
    @BindView(R.id.lvFlowingRecord)
    ListView lvFlowingRecord;
    private FlowingRecordContract.Presenter presenter;

    private String typeArgs1;
    private String typeArgs2;
    TimePickerView pvStartTime;
    TimePickerView pvEndTime;
    public static FlowingRecordFragment newInstance(String type1, String type2) {
        FlowingRecordFragment fragment = new FlowingRecordFragment();
        Bundle args = new Bundle();
        args.putString(TYPE1, type1);
        args.putString(TYPE2, type2);
        fragment.setArguments(args);
        Injections.inject(null, fragment);
        return fragment;
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            typeArgs1 = getArguments().getString(TYPE1);
            typeArgs2 = getArguments().getString(TYPE2);
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_flowing_record;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {

        tvFlowingRecordBack.setMoreText(typeArgs2);
        tvFlowingRecordBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                finish();
            }
        });

        if ("today".equals(typeArgs1)) {
            presenter.postFlowingToday("", "FT", "0");
        } else {
            presenter.postFlowingHistory("", "FT", "0");
        }
        //时间选择器
        pvStartTime = new TimePickerBuilder(getContext(), new OnTimeSelectListener() {
            @Override
            public void onTimeSelect(Date date, View v) {
                tvFlowingRecordStartTime.setText(getTime(date));
            }
        })
                // .setLabel("年","月","日","时","分","秒")//默认设置为年月日时分秒
                .build();

        //时间选择器
        pvEndTime = new TimePickerBuilder(getContext(), new OnTimeSelectListener() {
            @Override
            public void onTimeSelect(Date date, View v) {
                tvFlowingRecordEndTime.setText(getTime(date));
            }
        })
                //  .setLabel("年","月","日","时","分","秒")//默认设置为年月日时分秒
                .build();
    }


    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
        return format.format(date);
    }
    @Override
    public void postFlowingRecordResult(FlowingRecordResult message) {
        GameLog.log("总共充值多少：" + message.getTotal());

        lvFlowingRecord.setAdapter(new RecordListAdapter(getContext(), R.layout.item_record, message.getRows()));
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    @Override
    public void setPresenter(FlowingRecordContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @OnClick({R.id.tvFlowingRecordStartTime, R.id.tvFlowingRecordEndTime, R.id.tvFlowingRecordToday, R.id.tvFlowingRecordLastDay, R.id.tvFlowingRecordLastWeek, R.id.tvFlowingRecordLastMonth, R.id.btnFlowingRecordSubmit})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.tvFlowingRecordStartTime:
                pvStartTime.show();
                break;
            case R.id.tvFlowingRecordEndTime:
                pvEndTime.show();
                break;
            case R.id.tvFlowingRecordToday:
                tvFlowingRecordStartTime.setText(DateHelper.getCurrentMonthDayBegin());
                tvFlowingRecordEndTime.setText(DateHelper.getToday());
                tvFlowingRecordToday.setTextColor(getContext().getColor(R.color.title_text));
                tvFlowingRecordLastDay.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvFlowingRecordLastWeek.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvFlowingRecordLastMonth.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvFlowingRecordToday.setBackgroundResource(R.drawable.bg_btn_focus);
                tvFlowingRecordLastDay.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvFlowingRecordLastWeek.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvFlowingRecordLastMonth.setBackgroundResource(R.drawable.bg_btn_no_focus);
                break;
            case R.id.tvFlowingRecordLastDay:
                tvFlowingRecordStartTime.setText(DateHelper.getFirstDayOfQuarter(new Date()));
                tvFlowingRecordEndTime.setText(DateHelper.getLastDayOfQuarter(new Date()));
                tvFlowingRecordToday.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvFlowingRecordLastDay.setTextColor(getContext().getColor(R.color.title_text));
                tvFlowingRecordLastWeek.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvFlowingRecordLastMonth.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvFlowingRecordToday.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvFlowingRecordLastDay.setBackgroundResource(R.drawable.bg_btn_focus);
                tvFlowingRecordLastWeek.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvFlowingRecordLastMonth.setBackgroundResource(R.drawable.bg_btn_no_focus);
                break;
            case R.id.tvFlowingRecordLastWeek:
                tvFlowingRecordStartTime.setText(DateHelper.getThisSeasonTime());
                tvFlowingRecordEndTime.setText(DateHelper.getToday());
                tvFlowingRecordToday.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvFlowingRecordLastDay.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvFlowingRecordLastWeek.setTextColor(getContext().getColor(R.color.title_text));
                tvFlowingRecordLastMonth.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvFlowingRecordToday.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvFlowingRecordLastDay.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvFlowingRecordLastWeek.setBackgroundResource(R.drawable.bg_btn_focus);
                tvFlowingRecordLastMonth.setBackgroundResource(R.drawable.bg_btn_no_focus);
                break;
            case R.id.tvFlowingRecordLastMonth:
                tvFlowingRecordStartTime.setText(DateHelper.getCurrentYearFirst());
                tvFlowingRecordEndTime.setText(DateHelper.getToday());
                tvFlowingRecordToday.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvFlowingRecordLastDay.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvFlowingRecordLastWeek.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvFlowingRecordLastMonth.setTextColor(getContext().getColor(R.color.title_text));
                tvFlowingRecordToday.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvFlowingRecordLastDay.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvFlowingRecordLastWeek.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvFlowingRecordLastMonth.setBackgroundResource(R.drawable.bg_btn_focus);
                break;
            case R.id.btnFlowingRecordSubmit:
                break;
        }
    }


    public class RecordListAdapter extends AutoSizeAdapter<RecordResult.RowsBean> {
        private Context context;

        public RecordListAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final RecordResult.RowsBean rowsBean, final int position) {


           /* if (rowsBean.getType().equals("S")) {
                holder.setText(R.id.tvRecordTypeAndName, rowsBean.getBank() + rowsBean.getNotes());
                holder.setText(R.id.tvRecordOrderCode, "汇款流水号" + rowsBean.getOrder_code());
                holder.setText(R.id.tvRecordTime, rowsBean.getDate());
                holder.setText(R.id.tvRecordMoney, "+" + rowsBean.getGold());
            } else {
                holder.setText(R.id.tvRecordTypeAndName, "转出到" + rowsBean.getBank_Address());
                holder.setText(R.id.tvRecordOrderCode, rowsBean.getName());
                holder.setText(R.id.tvRecordTime, rowsBean.getDate());
                holder.setText(R.id.tvRecordMoney, "-" + rowsBean.getGold());
            }*/


        }
    }

}
