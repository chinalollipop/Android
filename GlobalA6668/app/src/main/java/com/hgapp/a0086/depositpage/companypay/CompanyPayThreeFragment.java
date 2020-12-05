package com.hgapp.a0086.depositpage.companypay;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.StaggeredGridLayoutManager;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.builder.TimePickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.listener.OnTimeSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.bigkoo.pickerview.view.TimePickerView;
import com.hgapp.a0086.Injections;
import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseFragment;
import com.hgapp.a0086.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a0086.common.util.DoubleClickHelper;
import com.hgapp.a0086.common.widgets.GridRvItemDecoration;
import com.hgapp.a0086.common.widgets.NTitleBar;
import com.hgapp.a0086.data.DepositBankCordListResult;
import com.hgapp.common.util.Check;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class CompanyPayThreeFragment extends HGBaseFragment implements CompanyPayContract.View{

    private static final String ARG_PARAM0 = "param0";
    private static final String ARG_PARAM1 = "param1";
    @BindView(R.id.tvCompanyPayBack)
    NTitleBar tvCompanyPayBack;
    @BindView(R.id.etDepositCompanyPayMoney)
    EditText etDepositCompanyPayMoney;
    @BindView(R.id.etDepositCompanyPayName)
    EditText etDepositCompanyPayName;
    @BindView(R.id.tvDepositCompanyPayChannel)
    TextView tvDepositCompanyPayChannel;
    @BindView(R.id.tvDepositCompanyPayTime)
    TextView tvDepositCompanyPayTime;
    @BindView(R.id.edDepositCompanyPayRemark)
    EditText edDepositCompanyPayRemark;
    @BindView(R.id.btnDepositCompanyPaySubmit)
    Button btnDepositCompanyPaySubmit;
    @BindView(R.id.rvBalanceTransfer)
    RecyclerView flowBalanceTransfer;
    TimePickerView pvStartTime;
    OptionsPickerView optionsPickerViewChanel;
    DepositBankCordListResult.DataBean dataBean;
    private String bankId;
    private String getArgParam1;
    private CompanyPayContract.Presenter presenter;
    List<String> stringListChannel  = new ArrayList<String>();
    static List<String> searchRecordsArrayList  = new ArrayList<>();
    static {


        searchRecordsArrayList.add("100");
        searchRecordsArrayList.add("500");
        searchRecordsArrayList.add("1000");
        searchRecordsArrayList.add("5000");
        searchRecordsArrayList.add("10000");
        searchRecordsArrayList.add("50000");
    }
    public static CompanyPayThreeFragment newInstance(DepositBankCordListResult.DataBean dataBean, String getArgParam1) {
        CompanyPayThreeFragment fragment = new CompanyPayThreeFragment();
        Bundle args = new Bundle();
        args.putParcelable(ARG_PARAM0, dataBean);
        args.putString(ARG_PARAM1, getArgParam1);
        fragment.setArguments(args);
        Injections.inject(null,fragment);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            dataBean = getArguments().getParcelable(ARG_PARAM0);
            getArgParam1 = getArguments().getString(ARG_PARAM1);
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_companypay_three;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        stringListChannel.add(getString(R.string.deposite_bank_pay_channel1));
        stringListChannel.add(getString(R.string.deposite_bank_pay_channel2));
        stringListChannel.add(getString(R.string.deposite_bank_pay_channel3));
        stringListChannel.add(getString(R.string.deposite_bank_pay_channel4));
        stringListChannel.add(getString(R.string.games_prepare_bet_qita));
        tvCompanyPayBack.setMoreText(getArgParam1);
        tvCompanyPayBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                finish();
            }
        });
        bankId = dataBean.getId();
        /*tvDepositCompanyPayBank.setText(dataBean.getData().get(0).getBank_name()+dataBean.getData().get(0).getBank_user());
        tvDepositCompanyPayBankNumber.setText(dataBean.getData().get(0).getBank_account());
        tvDepositCompanyPayBankAddress.setText(dataBean.getData().get(0).getBank_addres());*/
        tvDepositCompanyPayChannel.setText(getString(R.string.deposite_bank_pay_channel1));
        tvDepositCompanyPayTime.setText(getTime(new Date()));

        //时间选择器
        pvStartTime = new TimePickerBuilder(getContext(), new OnTimeSelectListener() {
            @Override
            public void onTimeSelect(Date date, View v) {
                tvDepositCompanyPayTime.setText(getTime(date));
            }
        })
                .setType(new boolean[]{true, true, true, true, true, false})// 默认全部显示
                // .setLabel("年","月","日","时","分","秒")//默认设置为年月日时分秒
                .build();

        optionsPickerViewChanel = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                tvDepositCompanyPayChannel.setText(stringListChannel.get(options1));
            }
        }).build();
        optionsPickerViewChanel.setPicker(stringListChannel);

        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(),3, OrientationHelper.VERTICAL,false);
        flowBalanceTransfer.setLayoutManager(gridLayoutManager);
        flowBalanceTransfer.setAdapter(new RvMylistAdapter(getContext(),R.layout.item_balance_transfer,searchRecordsArrayList));

    }

    class RvMylistAdapter extends AutoSizeRVAdapter<String> {
        private Context context;
        public RvMylistAdapter(Context context, int layoutId,List<String> datas){
            super(context, layoutId, datas);
            this.context =  context;
        }
        @Override
        protected void convert(ViewHolder holder, final String dataBean1, final int position) {

            holder.setOnClickListener(R.id.tvItemBalanceTransfer, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    etDepositCompanyPayMoney.setText(dataBean1);
                }
            });

            holder.setText(R.id.tvItemBalanceTransfer,dataBean1);

        }
    }



    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm");
        return format.format(date);
    }

    private void onCheckThirdMobilePay() {
        String etMoney = etDepositCompanyPayMoney.getText().toString().trim();
        String etName =  etDepositCompanyPayName.getText().toString().trim();
        String tvChannel = tvDepositCompanyPayChannel.getText().toString().trim();
        String tvBank = dataBean.getBank_name() + dataBean.getBank_user();
        String tvTime = tvDepositCompanyPayTime.getText().toString().trim();
        String edRemark = edDepositCompanyPayRemark.getText().toString().trim();

        if (Check.isEmpty(etMoney)||Double.parseDouble(etMoney)<Double.parseDouble("100")) {
            super.showMessage(getString(R.string.deposite_usdt_limit_moeny));
            return;
        }

        if (Check.isEmpty(etName)) {
            super.showMessage(getString(R.string.deposite_aliqcpay_type_h));
            return;
        }

        presenter.postDepositCompanyPaySubimt("",bankId,etName,tvChannel,etMoney,tvTime,edRemark,tvBank);
        //EventBus.getDefault().post(new StartBrotherEvent(OnlinePlayFragment.newInstance(dataBean.getUrl(), thirdBankMoney, dataBean.getUserid(), dataBean.getId(), bankCode), SupportFragment.SINGLETASK));
    }

    @OnClick({R.id.btnDepositCompanyPaySubmit,R.id.tvDepositCompanyPayChannel,R.id.tvDepositCompanyPayTime,R.id.btnDepositCompanyPaySubmitReset})
    public void onViewClicked(View view ) {
        switch (view.getId()){
            case R.id.btnDepositCompanyPaySubmit:
                DoubleClickHelper.getNewInstance().disabledView(btnDepositCompanyPaySubmit);
                onCheckThirdMobilePay();
                break;
            case R.id.tvDepositCompanyPayTime:
                hideKeyboard();
                pvStartTime.show();
                break;
            case R.id.tvDepositCompanyPayChannel:
                hideKeyboard();
                optionsPickerViewChanel.show();
                break;
            case R.id.btnDepositCompanyPaySubmitReset://重置
                hideKeyboard();
                etDepositCompanyPayMoney.setText("");
                etDepositCompanyPayName.setText("");
                edDepositCompanyPayRemark.setText("");
                break;
        }

    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
        finish();
    }

    @Override
    public void setPresenter(CompanyPayContract.Presenter presenter) {
        this.presenter  = presenter;
    }


}
