package com.hgapp.betnew.depositpage.usdtpay;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.text.Editable;
import android.text.Html;
import android.text.TextWatcher;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import com.bigkoo.pickerview.view.OptionsPickerView;
import com.hgapp.betnew.Injections;
import com.hgapp.betnew.R;
import com.hgapp.betnew.base.HGBaseFragment;
import com.hgapp.betnew.base.IPresenter;
import com.hgapp.betnew.common.util.ACache;
import com.hgapp.betnew.common.util.CLipHelper;
import com.hgapp.betnew.common.util.CalcHelper;
import com.hgapp.betnew.common.util.DoubleClickHelper;
import com.hgapp.betnew.common.util.GameShipHelper;
import com.hgapp.betnew.common.util.HGConstant;
import com.hgapp.betnew.common.widgets.NTitleBar;
import com.hgapp.betnew.data.DepositAliPayQCCodeResult;
import com.hgapp.betnew.data.USDTRateResult;
import com.hgapp.betnew.homepage.online.OnlineFragment;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.squareup.picasso.Picasso;

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
    @BindView(R.id.tvUsdtMark)
    TextView tvUsdtMark;
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
    DepositAliPayQCCodeResult.DataBean dataBean;
    OptionsPickerView optionsPickerView;
    USDTRateResult usdtRateResult;

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


    //标记为红色
    private String onMarkRed(String sign){
        return " <font color='#FF0000'>" + sign+"</font>";
    }
    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        presenter.postUsdtRateApiSubimt("100");
        tvThirdBankBack.setMoreText(getArgParam1);
        etDepositThirdBankMoney.setText("100");
        etDepositThirdBankMoney.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {

            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {

            }

            @Override
            public void afterTextChanged(Editable s) {
                String money = s.toString().trim();
                if(Check.isEmpty(money)||"0".equals(money)){
                    onSetRate("0");
                    return;
                }
                if(Double.valueOf(money) >= Double.valueOf("100")){
                    //GameLog.log("当前存款大于 "+money);
                    money=  CalcHelper.divide(money,usdtRateResult.getUsdt_rate()).toString();
                    //GameLog.log("格式化的 "+GameShipHelper.formatNumber(money));
                    onSetRate(GameShipHelper.formatNumber(money));
                }else{
                    onSetRate("0");
                }
            }
        });
        GameLog.log("USDT当前存款的方式 "+dataBean.getType() +dataBean.getUsdt_name());
        tvDepositThirdBankChannel.setText(dataBean.getType());
        tvDepositThirdBankCode.setText(dataBean.getUsdt_name());
        tvThirdBankBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                finish();
            }
        });
        StringBuffer mark = new StringBuffer();
        //请注意：请在金额转出之后务必填写网页下方的汇款信息表格，以便我们财务人员能及时为您确认添加金额到您的会员账户。\n 本公司最低存款金额为100元，每次存款赠送最高1%红利。
        mark.append("请注意：<br>请在金额转出之后务必填写网页下方的汇款信息表格，以便我们财务人员能及时为您确认添加金额到您的会员账户。<br>本公司最低存款金额为").append("").
                append(onMarkRed(dataBean.getMin_deposit())).append("元，每次存款赠送最高").append(onMarkRed(dataBean.getYuhui_rate())).append("红利。");
        tvUsdtMark.setText(Html.fromHtml(mark.toString()));
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

        usdtTitle.setText(dataBean.getType());
        usdtAddress.setText(dataBean.getDeposit_address());
        //*注意
        // \n1.请勿想上述地址支付任何非TRC20 USDT资产，否则资产将无法找回。
        // \n2.当前Okex/火币/币安交易所USDT最新场外卖单单价6.75元。
        // \n3.请确保收款地址收到14.81 USDt[不含转账手续费]，否则无法到账。
        // \n 4.您支付至上述地址后，需要整个网络节点的确认，请耐心等待。

        StringBuffer service = new StringBuffer();
        service.append("*支付完成请等待").append(onMarkRed("5-10")).append("分钟到账,支付失败").append(onMarkRed("咨询客服"));
        usdtService.setText(Html.fromHtml(service.toString()));

        Picasso.with(getContext())
                .load(dataBean.getPhoto_name())
                .placeholder(R.drawable.loading)
                .into(ivDepositAliQCPayImage);
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

    @OnClick({R.id.btnDepositThirdBankSubmit,R.id.btnUSDTSubmit,R.id.usdtAddressCopy,R.id.usdtAmountCopy,R.id.usdtService})
    public void onViewClicked(View view) {
        switch (view.getId()){
            case R.id.btnDepositThirdBankSubmit:
                onCheckThirdMobilePay();
                break;
            case R.id.btnUSDTSubmit:

                EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(getArgParam1, dataBean.getTutorial_url())));

                //showMessage("当前访问的是 "+dataBean.getTutorial_url());
                break;
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
                EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(getArgParam1, webUrl)));

                // EventBus.getDefault().post(new ShowMainEvent(2));
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
        /*String thirdBankMoney = etDepositThirdBankMoney.getText().toString().trim();
        presenter.postUsdtRateApiSubimt(thirdBankMoney);*/
    }

    private void onSetRate(String amount){
        usdtAmount.setText( amount);
        StringBuffer mark = new StringBuffer();
        mark.append("*注意<br> 1.请勿向上述地址支付任何非"+onMarkRed(usdtRateResult.getType())+" USDT资产，否则资产将无法找回。<br> ")
                .append( "2.当前"+usdtRateResult.getJiaoyisuo()+"交易所USDT最新场外卖单单价").
                append(onMarkRed(usdtRateResult.getUsdt_rate())).
                append("元。<br>3.请确保收款地址收到").
                append(onMarkRed(amount)).
                append(" USDT").
                append(onMarkRed("[不含转账手续费]")).
                append(",否则无法到账。<br> 4.您支付至上述地址后，需要整个网络节点的确认，请耐心等待。");
        usdtMark.setText(Html.fromHtml(mark.toString()));
    }

    @Override
    public void postUsdtRateApiSubimtResult(USDTRateResult usdtRateResult) {
        this.usdtRateResult = usdtRateResult;
        GameLog.log("usdtRate "+usdtRateResult.toString());
        //EventBus.getDefault().post(new StartBrotherEvent(USDTQCPayFragment.newInstance(dataBean,usdtRateResult,getArgParam1)));
        onSetRate(usdtRateResult.getUsdt_amount());

    }

    @Override
    public void setPresenter(USDTPayContract.Presenter presenter) {
        this.presenter = presenter;
    }
}