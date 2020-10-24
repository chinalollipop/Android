package com.hgapp.a0086.depositpage.usdtpay;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.text.Html;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.TimePickerBuilder;
import com.bigkoo.pickerview.listener.OnTimeSelectListener;
import com.bigkoo.pickerview.view.TimePickerView;
import com.hgapp.a0086.Injections;
import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseFragment;
import com.hgapp.a0086.common.util.ACache;
import com.hgapp.a0086.common.util.CLipHelper;
import com.hgapp.a0086.common.util.DoubleClickHelper;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.common.widgets.NTitleBar;
import com.hgapp.a0086.data.DepositAliPayQCCodeResult;
import com.hgapp.a0086.data.USDTRateResult;
import com.hgapp.a0086.depositpage.aliqcpay.AliQCPayContract;
import com.hgapp.a0086.homepage.handicap.ShowMainEvent;
import com.hgapp.a0086.homepage.online.OnlineFragment;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.CopyUtil;
import com.squareup.picasso.Picasso;

import org.greenrobot.eventbus.EventBus;

import java.text.SimpleDateFormat;
import java.util.Date;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class USDTQCPayFragment extends HGBaseFragment implements USDTPayContract.View {

    private static final String ARG_PARAM0 = "param0";
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    private static final String ARG_PARAM3 = "param3";
    DepositAliPayQCCodeResult.DataBean dataBean;
    @BindView(R.id.tvAliQCPayBack)
    NTitleBar tvAliQCPayBack;
    @BindView(R.id.ivDepositAliQCPayImage)
    ImageView ivDepositAliQCPayImage;
    @BindView(R.id.usdtTitle)
    TextView usdtTitle;
    @BindView(R.id.usdtAmount)
    TextView usdtAmount;
    @BindView(R.id.usdtAddress)
    TextView usdtAddress;
    @BindView(R.id.usdtAmountCopy)
    Button usdtAmountCopy;
    @BindView(R.id.usdtAddressCopy)
    Button usdtAddressCopy;
    @BindView(R.id.usdtService)
    TextView usdtService;
    @BindView(R.id.usdtMark)
    TextView usdtMark;
    private String payId,payBankUser;
    private USDTRateResult getArgParam1;
    private String getArgParam2;

    private USDTPayContract.Presenter presenter;
    public static USDTQCPayFragment newInstance(DepositAliPayQCCodeResult.DataBean dataBean, USDTRateResult getArgParam1, String getArgParam2) {
        USDTQCPayFragment fragment = new USDTQCPayFragment();
        Bundle args = new Bundle();
        args.putParcelable(ARG_PARAM0, dataBean);
        args.putParcelable(ARG_PARAM1, getArgParam1);
        args.putString(ARG_PARAM2, getArgParam2);
        fragment.setArguments(args);
       //Injections.inject(fragment,null);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            dataBean = getArguments().getParcelable(ARG_PARAM0);
            getArgParam1 = getArguments().getParcelable(ARG_PARAM1);
            getArgParam2 = getArguments().getString(ARG_PARAM2);
        }
    }


    @Override
    public int setLayoutId() {
        return R.layout.fragment_usdtqcpay;
    }


    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm");
        return format.format(date);
    }

    //标记为红色
    private String onMarkRed(String sign){
        return " <font color='#FF0000'>" + sign+"</font>";
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {

        usdtTitle.setText(getArgParam1.getType());
        usdtAmount.setText(getArgParam1.getUsdt_amount());

        usdtAddress.setText(dataBean.getDeposit_address());
        //*注意
        // \n1.请勿想上述地址支付任何非TRC20 USDT资产，否则资产将无法找回。
        // \n2.当前Okex/火币/币安交易所USDT最新场外卖单单价6.75元。
        // \n3.请确保收款地址收到14.81 USDt[不含转账手续费]，否则无法到账。
        // \n 4.您支付至上述地址后，需要整个网络节点的确认，请耐心等待。
        StringBuffer mark = new StringBuffer();
        mark.append("*注意<br>1.请勿向上述地址支付任何非"+onMarkRed(getArgParam1.getType())+" USDT资产，否则资产将无法找回。<br> ")
        .append( "2.当前"+getArgParam1.getJiaoyisuo()+"交易所USDT最新场外卖单单价").
                append(onMarkRed(getArgParam1.getUsdt_rate())).
                append("元。<br>3.请确保收款地址收到").
                append(onMarkRed(getArgParam1.getUsdt_amount())).
                append(" USDT").
                append(onMarkRed("[不含转账手续费]")).
                append(",否则无法到账。<br> 4.您支付至上述地址后，需要整个网络节点的确认，请耐心等待。");
        usdtMark.setText(Html.fromHtml(mark.toString()));
        StringBuffer service = new StringBuffer();
        service.append("*支付完成请等待").append(onMarkRed("5-10")).append("分钟到账,支付失败").append(onMarkRed("咨询客服"));
        usdtService.setText(Html.fromHtml(service.toString()));
        tvAliQCPayBack.setMoreText(getArgParam2);
        tvAliQCPayBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                finish();
            }
        });
        Picasso.with(getContext())
                .load(dataBean.getPhoto_name())
                .placeholder(R.drawable.loading)
                .into(ivDepositAliQCPayImage);

    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void setPresenter(USDTPayContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @OnClick({R.id.usdtAddressCopy,R.id.usdtAmountCopy,R.id.usdtService})
    public void onViewClicked(View view) {
        switch (view.getId()){
            case R.id.usdtAddressCopy:
                showMessage("复制成功！");
                DoubleClickHelper.getNewInstance().disabledView(usdtAddressCopy);
                CLipHelper.copy(getContext(),usdtAddress.getText().toString());
                break;
            case R.id.usdtAmountCopy:
                showMessage("复制成功！");
                DoubleClickHelper.getNewInstance().disabledView(usdtAmountCopy);
                CLipHelper.copy(getContext(),usdtAmount.getText().toString());
                break;
            case R.id.usdtService:
                String webUrl = ACache.get(getContext()).getAsString(HGConstant.USERNAME_SERVICE_URL);
                if(Check.isEmpty(webUrl)){
                    webUrl = HGConstant.USERNAME_SERVICE_DEFAULT_URL;
                }
                EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(getArgParam2, webUrl)));

               // EventBus.getDefault().post(new ShowMainEvent(2));
                break;
        }

    }


    @Override
    public void postDepositUSDTPaySubimtResult(String message) {
        showMessage(message);
        pop();
    }

    @Override
    public void postUsdtRateApiSubimtResult(USDTRateResult usdtRateResult) {

    }


}
