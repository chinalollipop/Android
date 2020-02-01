package com.nhg.xhg.withdrawPage;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.nhg.common.util.Check;
import com.nhg.xhg.Injections;
import com.nhg.xhg.R;
import com.nhg.xhg.base.HGBaseFragment;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.common.util.ACache;
import com.nhg.xhg.common.util.DoubleClickHelper;
import com.nhg.xhg.common.util.HGConstant;
import com.nhg.xhg.common.widgets.NTitleBar;
import com.nhg.xhg.data.WithdrawResult;

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
    }

    @Override
    public void postWithdrawResult(Object object) {
        showMessage(object.toString());
        pop();
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @OnClick(R.id.tvWithdrawBankSubmit)
    public void onViewClicked() {
        onCheckWithdrawSubmit();
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
        presenter.postWithdrawSubmit("",tvWithdrawBankAddress.getText().toString(),accountNumber,tvWithdrawBankName.getText().toString(),
                money,pwd, ACache.get(getContext()).getAsString(HGConstant.USERNAME_ALIAS),"Y");

    }

    @Override
    public void onVisible() {
        super.onVisible();
        presenter.postWithdrawBankCard("");
    }
}
