package com.hgapp.a6668.depositpage.usdtpay;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.bigkoo.pickerview.view.OptionsPickerView;
import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.widgets.NTitleBar;
import com.hgapp.a6668.data.DepositAliPayQCCodeResult;
import com.hgapp.a6668.data.USDTRateResult;
import com.hgapp.a6668.homepage.online.OnlineFragment;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;

import org.greenrobot.eventbus.EventBus;

import java.text.SimpleDateFormat;
import java.util.Arrays;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class USDTPayFragment extends HGBaseFragment implements USDTPayContract.View {

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
    @BindView(R.id.btnUSDTSubmit)
    Button btnUSDTSubmit;

    DepositAliPayQCCodeResult.DataBean dataBean;
    OptionsPickerView optionsPickerView;

    private USDTPayContract.Presenter presenter;
    private String getArgParam1;
    private String bankCode;
    public static USDTPayFragment newInstance(DepositAliPayQCCodeResult.DataBean dataBean, String getArgParam1) {
        USDTPayFragment fragment = new USDTPayFragment();
        Bundle args = new Bundle();
        args.putParcelable(ARG_PARAM0,dataBean);
        args.putString(ARG_PARAM1,getArgParam1);
        fragment.setArguments(args);
        Injections.inject(fragment,null);
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
        return R.layout.fragment_usdtpay;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        tvThirdBankBack.setMoreText(getArgParam1);
        GameLog.log("USDT当前存款的方式 "+dataBean.getType() +dataBean.getUsdt_name());
        tvDepositThirdBankChannel.setText(dataBean.getType());
        tvDepositThirdBankCode.setText(dataBean.getUsdt_name());
        tvThirdBankBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                finish();
            }
        });

       /* List<String> stringList  = new ArrayList<String>();
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
        bankCode = dataBean.getBankList().get(0).getBankcode();*/
    }

    private void onCheckThirdMobilePay(){
        String thirdBankMoney = etDepositThirdBankMoney.getText().toString().trim();
        if (Check.isEmpty(thirdBankMoney)||Double.parseDouble(thirdBankMoney)<Double.parseDouble("100")) {
            super.showMessage("汇款金额须大于100元！");
            return;
        }
        presenter.postDepositUSDTPaySubimt("", dataBean.getId(),  thirdBankMoney, getTime(new Date()), "",dataBean.getBank_user());
        //EventBus.getDefault().post(new StartBrotherEvent(OnlinePlayFragment.newInstance(dataBean.getUrl(),thirdBankMoney,dataBean.getUserid(),dataBean.getId(),bankCode), SupportFragment.SINGLETASK));
    }

    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm");
        return format.format(date);
    }

    @OnClick({R.id.btnDepositThirdBankSubmit,R.id.btnUSDTSubmit})
    public void onViewClicked(View view) {
        switch (view.getId()){
            case R.id.btnDepositThirdBankSubmit:
                onCheckThirdMobilePay();
                break;
            case R.id.btnUSDTSubmit:

                EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(getArgParam1, dataBean.getTutorial_url())));

                //showMessage("当前访问的是 "+dataBean.getTutorial_url());
                break;
        }

    }

    @OnClick(R.id.tvDepositThirdBankCode)
    public void onViewBankCodeClicked() {
        //optionsPickerView.show();

    }

    @Override
    public void postDepositUSDTPaySubimtResult(String message) {
        showMessage(message);
        String thirdBankMoney = etDepositThirdBankMoney.getText().toString().trim();
        presenter.postUsdtRateApiSubimt(thirdBankMoney);
    }

    @Override
    public void postUsdtRateApiSubimtResult(USDTRateResult usdtRateResult) {
        EventBus.getDefault().post(new StartBrotherEvent(USDTQCPayFragment.newInstance(dataBean,usdtRateResult,getArgParam1)));

    }

    @Override
    public void setPresenter(USDTPayContract.Presenter presenter) {
        this.presenter = presenter;
    }
}
