package com.hgapp.a6668.depositpage.companypay;

import android.os.Bundle;
import android.support.annotation.Nullable;
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
import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.common.util.DoubleClickHelper;
import com.hgapp.a6668.common.widgets.NTitleBar;
import com.hgapp.a6668.data.DepositBankCordListResult;
import com.hgapp.common.util.Check;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class CompanyPayFragment extends HGBaseFragment implements CompanyPayContract.View{

    private static final String ARG_PARAM0 = "param0";
    private static final String ARG_PARAM1 = "param1";
    @BindView(R.id.tvCompanyPayBack)
    NTitleBar tvCompanyPayBack;
    @BindView(R.id.etDepositCompanyPayMoney)
    EditText etDepositCompanyPayMoney;
    @BindView(R.id.etDepositCompanyPayName)
    EditText etDepositCompanyPayName;
    @BindView(R.id.tvDepositCompanyPayBank)
    TextView tvDepositCompanyPayBank;
    @BindView(R.id.tvDepositCompanyPayBankNumber)
    TextView tvDepositCompanyPayBankNumber;
    @BindView(R.id.tvDepositCompanyPayBankAddress)
    TextView tvDepositCompanyPayBankAddress;
    @BindView(R.id.tvDepositCompanyPayChannel)
    TextView tvDepositCompanyPayChannel;
    @BindView(R.id.tvDepositCompanyPayTime)
    TextView tvDepositCompanyPayTime;
    @BindView(R.id.edDepositCompanyPayRemark)
    EditText edDepositCompanyPayRemark;
    @BindView(R.id.btnDepositCompanyPaySubmit)
    Button btnDepositCompanyPaySubmit;
    TimePickerView pvStartTime;
    OptionsPickerView optionsPickerViewBank;
    OptionsPickerView optionsPickerViewChanel;
    DepositBankCordListResult dataBean;
    private String bankId;
    private String getArgParam1;
    private CompanyPayContract.Presenter presenter;
    static List<String> stringListChannel  = new ArrayList<String>();
    List<String> stringListBankName  = new ArrayList<String>();
    static {
        stringListChannel.add("银行柜台");
        stringListChannel.add("ATM现金");
        stringListChannel.add("ATM卡转");
        stringListChannel.add("网银转账");
        stringListChannel.add("其他");
    }
    public static CompanyPayFragment newInstance(DepositBankCordListResult dataBean,String getArgParam1) {
        CompanyPayFragment fragment = new CompanyPayFragment();
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
        return R.layout.fragment_companypay;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        tvCompanyPayBack.setMoreText(getArgParam1);
        tvCompanyPayBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                finish();
            }
        });
        bankId = dataBean.getData().get(0).getId();
        if((dataBean.getData().get(0).getBank_name()+dataBean.getData().get(0).getBank_user()).length()>=10){
            tvDepositCompanyPayBank.setTextSize(12);
        }
        tvDepositCompanyPayBank.setText(dataBean.getData().get(0).getBank_name()+dataBean.getData().get(0).getBank_user());
        tvDepositCompanyPayBankNumber.setText(dataBean.getData().get(0).getBank_account());
        tvDepositCompanyPayBankAddress.setText(dataBean.getData().get(0).getBank_addres());
        tvDepositCompanyPayChannel.setText("银行柜台");
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

        
        int alSize = dataBean.getData().size();
        for(int i=0;i<alSize;++i){
            stringListBankName.add(dataBean.getData().get(i).getBank_name()+dataBean.getData().get(i).getBank_user());
        }
        optionsPickerViewBank = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                if(stringListBankName.get(options1).length()>10){
                    tvDepositCompanyPayBank.setTextSize(12);
                }else{
                    tvDepositCompanyPayBank.setTextSize(14);
                }
                tvDepositCompanyPayBank.setText(stringListBankName.get(options1));
                tvDepositCompanyPayBankNumber.setText(dataBean.getData().get(options1).getBank_account());
                tvDepositCompanyPayBankAddress.setText(dataBean.getData().get(options1).getBank_addres());
            }
        }).build();
        optionsPickerViewBank.setPicker(stringListBankName);

        optionsPickerViewChanel = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                tvDepositCompanyPayChannel.setText(stringListChannel.get(options1));
            }
        }).build();
        optionsPickerViewChanel.setPicker(stringListChannel);
    }


    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm");
        return format.format(date);
    }

    private void onCheckThirdMobilePay() {
        String etMoney = etDepositCompanyPayMoney.getText().toString().trim();
        String etName =  etDepositCompanyPayName.getText().toString().trim();
        String tvChannel = tvDepositCompanyPayChannel.getText().toString().trim();
        String tvBank = tvDepositCompanyPayBank.getText().toString().trim();
        String tvTime = tvDepositCompanyPayTime.getText().toString().trim();
        String edRemark = edDepositCompanyPayRemark.getText().toString().trim();

        if (Check.isEmpty(etMoney)) {
            showMessage("汇款金额必须是整数！");
            return;
        }

        if (Check.isEmpty(etName)) {
            showMessage("请输入存款人姓名！");
            return;
        }


        presenter.postDepositCompanyPaySubimt("",bankId,etName,tvChannel,etMoney,tvTime,edRemark,tvBank);
        //EventBus.getDefault().post(new StartBrotherEvent(OnlinePlayFragment.newInstance(dataBean.getUrl(), thirdBankMoney, dataBean.getUserid(), dataBean.getId(), bankCode), SupportFragment.SINGLETASK));
    }

    @OnClick({R.id.btnDepositCompanyPaySubmit,R.id.tvDepositCompanyPayBank,R.id.tvDepositCompanyPayChannel,R.id.tvDepositCompanyPayTime})
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
            case R.id.tvDepositCompanyPayBank:
                hideKeyboard();
                optionsPickerViewBank.show();
                break;
        }

    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
        pop();
    }

    @Override
    public void setPresenter(CompanyPayContract.Presenter presenter) {
        this.presenter  = presenter;
    }


}
