package com.hgapp.betnhg.withdrawPage;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.text.Editable;
import android.text.Html;
import android.text.TextWatcher;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.hgapp.betnhg.Injections;
import com.hgapp.betnhg.R;
import com.hgapp.betnhg.base.HGBaseFragment;
import com.hgapp.betnhg.base.IPresenter;
import com.hgapp.betnhg.common.util.ACache;
import com.hgapp.betnhg.common.util.DoubleClickHelper;
import com.hgapp.betnhg.common.util.HGConstant;
import com.hgapp.betnhg.common.widgets.NTitleBar;
import com.hgapp.betnhg.data.USDTRateResult;
import com.hgapp.betnhg.data.WithdrawResult;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;

import java.math.BigDecimal;
import java.text.DecimalFormat;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class WithdrawFragment extends HGBaseFragment implements WithdrawContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    @BindView(R.id.tvWithdrawBankBack)
    NTitleBar tvWithdrawBankBack;
    @BindView(R.id.tvWithdrawOweBet)
    TextView tvWithdrawOweBet;
    @BindView(R.id.tvWithdrawTotalBet)
    TextView tvWithdrawTotalBet;
    @BindView(R.id.tvWithdrawBankAddress)
    TextView tvWithdrawBankAddress;
    @BindView(R.id.tvWithdrawBankAccount)
    TextView tvWithdrawBankAccount;
    @BindView(R.id.tvWithdrawBankName)
    TextView tvWithdrawBankName;
    @BindView(R.id.tvWithdrawBankMoney)
    EditText tvWithdrawBankMoney;
    @BindView(R.id.tvWithdrawBankPwd)
    EditText tvWithdrawBankPwd;
    @BindView(R.id.tvWithdrawBankSubmit)
    Button tvWithdrawBankSubmit;
    @BindView(R.id.usdtRButton)
    TextView usdtRButton;
    @BindView(R.id.bankRButton)
    TextView bankRButton;
    @BindView(R.id.layUsdtRate)
    LinearLayout layUsdtRate;
    @BindView(R.id.tvWithdrawUsdtMoney)
    TextView tvWithdrawUsdtMoney;
    @BindView(R.id.tvWithdrawUsdtRate)
    TextView tvWithdrawUsdtRate;
    @BindView(R.id.tvWithdrawUsdtAddress)
    TextView tvWithdrawUsdtAddress;
    @BindView(R.id.bankName)
    TextView bankName;
    private String bankNameString;
    private String usdtRate;
    private String usdtAddress;
    private boolean isUsdtWithdraw;
    private String accountNumber;
    private String typeArgs1;
    private String typeArgs2;
    private WithdrawResult WithdrawResult;
    private WithdrawContract.Presenter presenter;

    public static WithdrawFragment newInstance() {
        WithdrawFragment fragment = new WithdrawFragment();
        Bundle args = new Bundle();
        fragment.setArguments(args);
        Injections.inject(null, fragment);
        return fragment;
    }

    public static WithdrawFragment newInstance(String type1,String type2) {
        WithdrawFragment fragment = new WithdrawFragment();
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
    public void setPresenter(WithdrawContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_withdraw;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        tvWithdrawBankBack.setMoreText(typeArgs1);
        tvWithdrawBankBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                pop();
            }
        });

        tvWithdrawBankMoney.addTextChangedListener(new TextWatcher(){

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {

            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {

            }

            @Override
            public void afterTextChanged(Editable s) {
                String sdata = tvWithdrawBankMoney.getText().toString().trim();
                if(!Check.isEmpty(sdata)&&!Check.isEmpty(usdtRate)&&Double.valueOf(sdata)>=100){
                    BigDecimal a1 = new BigDecimal(sdata);
                    BigDecimal b1 = new BigDecimal(usdtRate);
                    //double result = a1.divide(b1,2, BigDecimal.ROUND_HALF_UP).doubleValue();
                    BigDecimal result = a1.divide(b1,2, BigDecimal.ROUND_HALF_UP);
                    GameLog.log(result+"  结果是");

                    //BigDecimal one = new BigDecimal("2");
                    //double a = result.divide(one,2,BigDecimal.ROUND_HALF_UP).doubleValue();//保留1位数
                    tvWithdrawUsdtMoney.setText(formatToNumber(result));
                    //tvWithdrawUsdtMoney.setText(result+"");
                }else{
                    tvWithdrawUsdtMoney.setText("0.00");
                }
            }
        });

    }

    /**
     * @desc 1.0~1之间的BigDecimal小数，格式化后失去前面的0,则前面直接加上0。
     * 2.传入的参数等于0，则直接返回字符串"0.00"
     * 3.大于1的小数，直接格式化返回字符串
     * @param obj 传入的小数
     * @return
     */
    public static String formatToNumber(BigDecimal obj) {
        DecimalFormat df = new DecimalFormat("#.00");
        if(obj.compareTo(BigDecimal.ZERO)==0) {
            return "0.00";
        }else if(obj.compareTo(BigDecimal.ZERO)>0&&obj.compareTo(new BigDecimal(1))<0){
            return "0"+df.format(obj).toString();
        }else if(obj.compareTo(BigDecimal.ZERO)<0&&obj.compareTo(new BigDecimal(-1))>0){
            df = new DecimalFormat("0.00");
            return df.format(obj);
        }else {
            return df.format(obj).toString();
        }
    }


    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void postWithdrawResult(WithdrawResult withdrawResult) {

        this.WithdrawResult= withdrawResult;
        tvWithdrawOweBet.setText(withdrawResult.getOwe_bet());
        tvWithdrawTotalBet.setText(withdrawResult.getTotal_bet());
        tvWithdrawBankAddress.setText(withdrawResult.getBank_Address());
        //前6后3
        accountNumber = withdrawResult.getBank_Account();
        String account = accountNumber.substring(0,6)+"******"+accountNumber.substring(accountNumber.length()-3);
        tvWithdrawBankAccount.setText(account);
        tvWithdrawBankName.setText(withdrawResult.getBank_Name());
        bankNameString= withdrawResult.getBank_Name();
    }

    @Override
    public void postWithdrawResult(Object object) {
        showMessage(object.toString());
        pop();
    }

    @Override
    public void postUsdtRateApiSubimtResult(USDTRateResult usdtRateResult) {
        usdtAddress = usdtRateResult.getUsdt_Address();
        String account = usdtAddress.substring(0,6)+"******"+usdtAddress.substring(usdtAddress.length()-3);
        tvWithdrawUsdtAddress.setText(account);
        usdtRate = usdtRateResult.getWithdrawals_usdt_rate();
        String usdtrate  = "实时汇率："+onMarkRed(usdtRate);
        tvWithdrawUsdtRate.setText(Html.fromHtml(usdtrate.toString()));
    }

    //标记为红色
    private String onMarkRed(String sign){
        return " <font color='#FF0000'>" + sign+"</font>";
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @OnClick({R.id.tvWithdrawBankSubmit,R.id.bankRButton,R.id.usdtRButton})
    public void onViewClicked(View view) {
        switch (view.getId()){
            case R.id.tvWithdrawBankSubmit:
                onCheckWithdrawSubmit();
                break;
            case R.id.bankRButton:
                isUsdtWithdraw = false;
                bankName.setText("所在银行");
                tvWithdrawBankName.setText(bankNameString);
                layUsdtRate.setVisibility(View.GONE);
                bankRButton.setCompoundDrawablesWithIntrinsicBounds(getResources().getDrawable(R.drawable.cert_selet),null,null,null);
                usdtRButton.setCompoundDrawablesWithIntrinsicBounds(getResources().getDrawable(R.drawable.cert_no_select),null,null,null);
                break;
            case R.id.usdtRButton:
                if(Check.isEmpty(usdtAddress)){
                    showMessage("请您先绑定USDT提款地址");
                    return;
                }
                bankName.setText("所属币种");
                tvWithdrawBankName.setText("USDT(TRC20)");
                isUsdtWithdraw = true;
                layUsdtRate.setVisibility(View.VISIBLE);
                usdtRButton.setCompoundDrawablesWithIntrinsicBounds(getResources().getDrawable(R.drawable.cert_selet),null,null,null);
                bankRButton.setCompoundDrawablesWithIntrinsicBounds(getResources().getDrawable(R.drawable.cert_no_select),null,null,null);

                break;
        }

    }

    @OnClick(R.id.tvWithdrawTotalBetDetail)
    public void onViewClickedDetail() {
        if(Check.isNull(WithdrawResult)||Check.isNull(WithdrawResult.getBet_list())){
            showMessage("请求数据有误，请稍后再试！");
            return;
        }
        WithDrawDetailFragment.newInstance(WithdrawResult).show(getFragmentManager());
    }

    private void onCheckWithdrawSubmit(){


        String money = tvWithdrawBankMoney.getText().toString().trim();
        String pwd = tvWithdrawBankPwd.getText().toString().trim();

        if(Check.isEmpty(money)){
            showMessage("请输入提款金额！");
            return;
        }
        if(Check.isEmpty(pwd)){
            showMessage("请输入提款密码！");
            return;
        }
        if(Integer.parseInt(money)<100){
            showMessage("提款金额最少100元！");
            return;
        }
        DoubleClickHelper.getNewInstance().disabledView(tvWithdrawBankSubmit);
        if(isUsdtWithdraw){
            presenter.postWithdrawSubmit("",tvWithdrawBankAddress.getText().toString(),accountNumber,tvWithdrawBankName.getText().toString(),
                    money,pwd, ACache.get(getContext()).getAsString(HGConstant.USERNAME_ALIAS),"Y",usdtAddress);
        }else{
            presenter.postWithdrawSubmit("",tvWithdrawBankAddress.getText().toString(),accountNumber,tvWithdrawBankName.getText().toString(),
                    money,pwd, ACache.get(getContext()).getAsString(HGConstant.USERNAME_ALIAS),"Y","");
        }


    }

    @Override
    public void onVisible() {
        super.onVisible();
        presenter.postWithdrawBankCard("");
        presenter.postUsdtRateApiSubimt("getUsdtAddress");
    }
}
