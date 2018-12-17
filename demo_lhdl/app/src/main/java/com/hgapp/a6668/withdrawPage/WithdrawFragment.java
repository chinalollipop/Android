package com.hgapp.a6668.withdrawPage;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.DoubleClickHelper;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.widgets.NTitleBar;
import com.hgapp.a6668.data.WithdrawResult;
import com.hgapp.common.util.Check;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class WithdrawFragment extends HGBaseFragment implements WithdrawContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    @BindView(R.id.tvWithdrawBankBack)
    NTitleBar tvWithdrawBankBack;
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

    private String typeArgs1;
    private String typeArgs2;
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

        tvWithdrawBankAddress.setText(withdrawResult.getBank_Address());
        tvWithdrawBankAccount.setText(withdrawResult.getBank_Account());
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
        presenter.postWithdrawSubmit("",tvWithdrawBankAddress.getText().toString(),tvWithdrawBankAccount.getText().toString(),tvWithdrawBankName.getText().toString(),
                money,pwd, ACache.get(getContext()).getAsString(HGConstant.USERNAME_ALIAS),"Y");

    }

    @Override
    public void onVisible() {
        super.onVisible();
        presenter.postWithdrawBankCard("");
    }
}