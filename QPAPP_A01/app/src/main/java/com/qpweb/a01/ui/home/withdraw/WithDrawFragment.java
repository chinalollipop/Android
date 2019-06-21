package com.qpweb.a01.ui.home.withdraw;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import com.qpweb.a01.Injections;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.data.BindCardResult;
import com.qpweb.a01.data.MemValidBetResult;
import com.qpweb.a01.ui.home.RefreshMoneyEvent;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.Check;
import com.qpweb.a01.utils.DoubleClickHelper;

import org.greenrobot.eventbus.EventBus;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class WithDrawFragment extends BaseDialogFragment implements WithDrawContract.View {


    String payId;//用户绑卡的id

    WithDrawContract.Presenter presenter;
    @BindView(R.id.withDrawAlias)
    TextView withDrawAlias;
    @BindView(R.id.withDrawAccount)
    TextView withDrawAccount;
    @BindView(R.id.withDrawAdds)
    TextView withDrawAdds;
    @BindView(R.id.withDrawName)
    TextView withDrawName;
    @BindView(R.id.withDrawPwd)
    EditText withDrawPwd;
    @BindView(R.id.withDrawMoney)
    EditText withDrawMoney;
    @BindView(R.id.withDrawSubmit)
    TextView withDrawSubmit;
    @BindView(R.id.withDrawClose)
    ImageView withDrawClose;

    public static WithDrawFragment newInstance() {
        Bundle bundle = new Bundle();
        WithDrawFragment loginFragment = new WithDrawFragment();
        Injections.inject(loginFragment, null);
        loginFragment.setArguments(bundle);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.with_draw_fragment;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
        }

    }

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        presenter.postMemValidBet("", "");
        /*String userName = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_ACCOUNT);
        String pwd = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_PWD);*/
    }

    private void onCheckAndSubmit() {
        String realName = withDrawAlias.getText().toString().trim();
        String account = withDrawAccount.getText().toString().trim();
        String adds = withDrawAdds.getText().toString().trim();
        String name = withDrawName.getText().toString().trim();
        String pwd = withDrawPwd.getText().toString().trim();
        String money = withDrawMoney.getText().toString().trim();
        if (Check.isEmpty(pwd)) {
            showMessage("请输入资金密码");
            return;
        }
        if (Check.isEmpty(money)) {
            showMessage("请输入取款金额");
            return;
        }
        presenter.postWithDraw("", adds, account, name, money,pwd,realName);
    }

    @Override
    public void postMemValidBetResult(MemValidBetResult memValidBetResult) {
       MemValidBetResult.UserBankAccountBean userBankAccountBean=  memValidBetResult.getUser_bank_account();
       if(!Check.isNull(userBankAccountBean)){
           if(!Check.isEmpty(userBankAccountBean.getAlias())){
               withDrawAlias.setText(userBankAccountBean.getAlias());
           }
           if(!Check.isEmpty(userBankAccountBean.getBank_Account())){
               withDrawAccount.setText(userBankAccountBean.getBank_Account());
           }
           if(!Check.isEmpty(userBankAccountBean.getBank_Address())){
               withDrawAdds.setText(userBankAccountBean.getBank_Address());
           }
           if(!Check.isEmpty(userBankAccountBean.getBank_Name())){
               withDrawName.setText(userBankAccountBean.getBank_Name());
           }

       }
    }

    @Override
    public void postMemValidBetErrorResult() {
        hide();
    }

    @Override
    public void postWithDrawResult(BindCardResult bindCardResult) {
        EventBus.getDefault().post(new RefreshMoneyEvent());
        hide();
    }

    @Override
    public void setPresenter(WithDrawContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    @OnClick({R.id.withDrawSubmit, R.id.withDrawClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.withDrawSubmit:
                DoubleClickHelper.getNewInstance().disabledView(withDrawSubmit);
                onCheckAndSubmit();
                break;
            case R.id.withDrawClose:
                hide();
                break;
        }
    }
}
