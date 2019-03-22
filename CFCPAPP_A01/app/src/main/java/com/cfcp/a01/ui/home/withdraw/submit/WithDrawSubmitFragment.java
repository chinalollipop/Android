package com.cfcp.a01.ui.home.withdraw.submit;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.TextView;

import com.cfcp.a01.CFConstant;
import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.WithDrawNextResult;
import com.cfcp.a01.data.WithDrawResult;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class WithDrawSubmitFragment extends BaseFragment implements WithDrawSubmitContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.withdrawSubmitBack)
    NTitleBar withdrawSubmitBack;
    @BindView(R.id.withdrawSubmitName)
    TextView withdrawSubmitName;
    @BindView(R.id.withdrawSubmitMoney)
    TextView withdrawSubmitMoney;
    @BindView(R.id.withdrawSubmitAmount)
    TextView withdrawSubmitAmount;
    @BindView(R.id.withdrawSubmitbank)
    TextView withdrawSubmitbank;
    @BindView(R.id.withdrawSubmitbranch_address)
    TextView withdrawSubmitbranchAddress;
    @BindView(R.id.withdrawSubmitaccount_name)
    TextView withdrawSubmitaccountName;
    @BindView(R.id.withdrawSubmitaccount)
    TextView withdrawSubmitaccount;
    @BindView(R.id.withdrawSubmitFundPwd)
    EditText withdrawSubmitFundPwd;
    @BindView(R.id.withdrawSubmitSubmit)
    TextView withdrawSubmitSubmit;
    private String typeArgs2, typeArgs3;
    WithDrawSubmitContract.Presenter presenter;

    WithDrawNextResult withDrawNextResult;
    String id,amount;

    public static WithDrawSubmitFragment newInstance(WithDrawNextResult withDrawNextResult, String money) {
        WithDrawSubmitFragment betFragment = new WithDrawSubmitFragment();
        Bundle args = new Bundle();
        args.putParcelable(TYPE2, withDrawNextResult);
        args.putString(TYPE3, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_withdraw_submit;
    }


    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            withDrawNextResult = getArguments().getParcelable(TYPE2);
            typeArgs3 = getArguments().getString(TYPE3);
        }
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        withdrawSubmitBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        id = withDrawNextResult.getABankCard().getId()+"";
        amount = withDrawNextResult.getAInputData().getAmount();
        withdrawSubmitName.setText(withDrawNextResult.getABankCard().getUsername());
        withdrawSubmitMoney.setText(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_BALANCE));
        withdrawSubmitAmount.setText(withDrawNextResult.getAInputData().getAmount());
        withdrawSubmitbank.setText(withDrawNextResult.getABankCard().getBank());
        withdrawSubmitbranchAddress.setText(withDrawNextResult.getABankCard().getBranch_address());
        withdrawSubmitaccount.setText(withDrawNextResult.getABankCard().getAccount());
        withdrawSubmitaccountName.setText(withDrawNextResult.getABankCard().getAccount_name());
    }


    //请求数据接口
    private void onRequsetData() {
        String fundPwd = withdrawSubmitFundPwd.getText().toString().trim();
        if (Check.isEmpty(fundPwd)) {
            showMessage("请输入资金密码");
            return;
        }
        presenter.getWithDrawSubmit(id,amount, fundPwd);
    }


    @Override
    public void getWithDrawSubmitResult(WithDrawNextResult withDrawNextResult) {
        GameLog.log("取款输入金额点击下一步  成功");
        showMessage("本次提款提案已经提交，请稍后查看资金");
        finish();
    }

    @Override
    public void setPresenter(WithDrawSubmitContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void onSupportVisible() {
        super.onSupportVisible();
    }



    @OnClick(R.id.withdrawSubmitSubmit)
    public void onViewClicked() {
        onRequsetData();
    }
}
