package com.hgapp.a0086.depositpage.thirdbankcardpay;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseFragment;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.common.widgets.NTitleBar;
import com.hgapp.a0086.data.DepositThirdBankCardResult;
import com.hgapp.a0086.depositpage.DepositeContract;
import com.hgapp.a0086.depositpage.thirdmobilepay.OnlinePlayFragment;
import com.hgapp.common.util.Check;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class ThirdbankCardFragment extends HGBaseFragment {

    private static final String ARG_PARAM0 = "param0";
    private static final String ARG_PARAM1 = "param1";
    @BindView(R.id.tvThirdBankBack)
    NTitleBar tvThirdBankBack;
    @BindView(R.id.etDepositThirdBankMoney)
    EditText etDepositThirdBankMoney;
    @BindView(R.id.tvDepositThirdBankChannel)
    TextView tvDepositThirdBankChannel;
    @BindView(R.id.tvDepositThirdBankCode)
    TextView tvDepositThirdBankCode;
    @BindView(R.id.btnDepositThirdBankSubmit)
    Button btnDepositThirdBankSubmit;
    private DepositeContract.Presenter presenter;
    DepositThirdBankCardResult.DataBean dataBean;
    OptionsPickerView  optionsPickerView;
    private String getArgParam1;
    private String bankCode;
    public static ThirdbankCardFragment newInstance(DepositThirdBankCardResult.DataBean dataBean,String getArgParam1) {
        ThirdbankCardFragment fragment = new ThirdbankCardFragment();
        Bundle args = new Bundle();
        args.putParcelable(ARG_PARAM0,dataBean);
        args.putString(ARG_PARAM1,getArgParam1);
//        Injections.inject(null,fragment);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            dataBean = getArguments().getParcelable(ARG_PARAM0);
            getArgParam1= getArguments().getString(ARG_PARAM1);
        }
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_thirdbankcardpay;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        tvThirdBankBack.setMoreText(getArgParam1);
        tvThirdBankBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                finish();
            }
        });

        List<String> stringList  = new ArrayList<String>();
        int listSize = dataBean.getBankList().size();
        for(int i=0;i<listSize;++i){
            stringList.add(dataBean.getBankList().get(i).getBankname());
        }

        optionsPickerView = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                tvDepositThirdBankCode.setText(dataBean.getBankList().get(options1).getBankname());
                bankCode = dataBean.getBankList().get(options1).getBankcode();
            }
        }).build();
        optionsPickerView.setPicker(stringList);
        optionsPickerView.isDialog();
        tvDepositThirdBankChannel.setText(dataBean.getTitle());
        tvDepositThirdBankCode.setText(dataBean.getBankList().get(0).getBankname());
        bankCode = dataBean.getBankList().get(0).getBankcode();
    }

    private void onCheckThirdMobilePay(){
        String thirdBankMoney = etDepositThirdBankMoney.getText().toString().trim();

        if(Check.isEmpty(thirdBankMoney)){
            showMessage("汇款金额必须是整数！");
            return;
        }
        EventBus.getDefault().post(new StartBrotherEvent(OnlinePlayFragment.newInstance(dataBean.getUrl(),thirdBankMoney,dataBean.getUserid(),dataBean.getId(),bankCode), SupportFragment.SINGLETASK));
    }

    @OnClick(R.id.btnDepositThirdBankSubmit)
    public void onViewClicked() {
        onCheckThirdMobilePay();
    }

    @OnClick(R.id.tvDepositThirdBankCode)
    public void onViewBankCodeClicked() {
        optionsPickerView.show();

    }
}
