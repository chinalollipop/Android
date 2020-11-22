package com.hgapp.a0086.depositpage.companypay;

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
import com.hgapp.a0086.Injections;
import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseFragment;
import com.hgapp.a0086.common.util.CLipHelper;
import com.hgapp.a0086.common.util.DoubleClickHelper;
import com.hgapp.a0086.common.widgets.NTitleBar;
import com.hgapp.a0086.data.DepositBankCordListResult;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.CopyUtil;

import org.greenrobot.eventbus.EventBus;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class CompanyPayTwoFragment extends HGBaseFragment {

    private static final String ARG_PARAM0 = "param0";
    private static final String ARG_PARAM1 = "param1";
    @BindView(R.id.tvCompanyPayBack)
    NTitleBar tvCompanyPayBack;
    @BindView(R.id.tvDepositCompanyPayBank)
    TextView tvDepositCompanyPayBank;
    @BindView(R.id.tvDepositCompanyPayBankCont)
    TextView tvDepositCompanyPayBankCont;
    @BindView(R.id.tvDepositCompanyPayBankNumber)
    TextView tvDepositCompanyPayBankNumber;
    @BindView(R.id.tvDepositCompanyPayBankAddress)
    TextView tvDepositCompanyPayBankAddress;
    @BindView(R.id.etDepositCompanyPayName)
    TextView etDepositCompanyPayName;
    @BindView(R.id.btnDepositCompanyPaySubmit)
    Button btnDepositCompanyPaySubmit;
    DepositBankCordListResult.DataBean dataBean;
    private String bankId;
    private String getArgParam1;
    private CompanyPayContract.Presenter presenter;
    List<String> stringListBankName  = new ArrayList<String>();
    public static CompanyPayTwoFragment newInstance(DepositBankCordListResult.DataBean dataBean, String getArgParam1) {
        CompanyPayTwoFragment fragment = new CompanyPayTwoFragment();
        Bundle args = new Bundle();
        args.putParcelable(ARG_PARAM0, dataBean);
        args.putString(ARG_PARAM1, getArgParam1);
        fragment.setArguments(args);
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
        return R.layout.fragment_companypay_two;
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
        bankId = dataBean.getId();
        /*{
                "bankcode": "BOC",
                "bank_account": "623*****9101",
                "bank_name": "中国银行",
                "bank_user": "广西桂林高绍友电子商务有限公司",
                "id": "39",
                "bank_addres": "中国银行"
        },*/
        tvDepositCompanyPayBank.setText(dataBean.getBank_name());
        tvDepositCompanyPayBankCont.setText(dataBean.getBank_context());
        etDepositCompanyPayName.setText(dataBean.getBank_user());
        tvDepositCompanyPayBankNumber.setText(dataBean.getBank_account());
        tvDepositCompanyPayBankAddress.setText(dataBean.getBank_addres());

    }


    @OnClick({R.id.btnDepositCompanyPaySubmit,R.id.etDepositCompanyPayNameCopy,R.id.tvDepositCompanyPayBankNumberCopy})
    public void onViewClicked(View view ) {
        switch (view.getId()){
            case R.id.btnDepositCompanyPaySubmit:
                DoubleClickHelper.getNewInstance().disabledView(btnDepositCompanyPaySubmit);
                EventBus.getDefault().post(new StartBrotherEvent(CompanyPayThreeFragment.newInstance(dataBean,getArgParam1), SupportFragment.SINGLETASK));
                break;
            case R.id.etDepositCompanyPayNameCopy:
                CLipHelper.copy(getContext(),etDepositCompanyPayName.getText().toString());
                showMessage(getString(R.string.comm_copy_succeed));
                break;
            case R.id.tvDepositCompanyPayBankNumberCopy:
                CLipHelper.copy(getContext(),tvDepositCompanyPayBankNumber.getText().toString());
                showMessage(getString(R.string.comm_copy_succeed));
                break;
        }

    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }


}
