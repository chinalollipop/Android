package com.hgapp.betnhg.depositpage.companypay;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.hgapp.betnhg.R;
import com.hgapp.betnhg.base.HGBaseFragment;
import com.hgapp.betnhg.common.util.CLipHelper;
import com.hgapp.betnhg.common.util.DoubleClickHelper;
import com.hgapp.betnhg.common.widgets.NTitleBar;
import com.hgapp.betnhg.data.DepositBankCordListResult;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
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
                showMessage("复制成功！");
                break;
            case R.id.tvDepositCompanyPayBankNumberCopy:
                CLipHelper.copy(getContext(),tvDepositCompanyPayBankNumber.getText().toString());
                showMessage("复制成功！");
                break;
        }

    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }


}
