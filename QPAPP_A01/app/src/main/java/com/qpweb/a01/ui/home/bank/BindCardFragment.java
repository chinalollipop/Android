package com.qpweb.a01.ui.home.bank;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.text.Editable;
import android.text.Spannable;
import android.text.SpannableString;
import android.text.TextWatcher;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import com.qpweb.a01.Injections;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.data.BankListResult;
import com.qpweb.a01.data.BindCardResult;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.Check;
import com.qpweb.a01.utils.DoubleClickHelper;
import com.qpweb.a01.utils.GameLog;
import com.qpweb.a01.widget.FormatBankCardTextWatcher;

import org.angmarch.views.NiceSpinner;
import org.angmarch.views.OnSpinnerItemSelectedListener;
import org.angmarch.views.SpinnerTextFormatter;
import org.greenrobot.eventbus.EventBus;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class BindCardFragment extends BaseDialogFragment implements BindCardContract.View{

    @BindView(R.id.bankCardRealName)
    EditText bankCardRealName;
    @BindView(R.id.bankCardAccount)
    EditText bankCardAccount;
    @BindView(R.id.bankCardAdds)
    EditText bankCardAdds;
    @BindView(R.id.bankCardId)
    NiceSpinner bankCardId;
    @BindView(R.id.bankCardSubmit)
    TextView bankCardSubmit;
    @BindView(R.id.setClose)
    ImageView setClose;

    String payId;//用户绑卡的id

    BindCardContract.Presenter presenter;

    public static BindCardFragment newInstance() {
        Bundle bundle = new Bundle();
        BindCardFragment loginFragment = new BindCardFragment();
        Injections.inject(loginFragment, null);
        loginFragment.setArguments(bundle);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.bind_card_fragment;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
        }

    }

    TextWatcher addCardAccountListener = new TextWatcher() {
        @Override
        public void beforeTextChanged(CharSequence s, int start, int count, int after) {
        }

        @Override
        public void afterTextChanged(Editable s) {
            String ms = s.toString().replace(" ","");
            if (Check.isEmpty(ms) || ms.length() < 4) {
                return;
            }
            String temp = "";
            //逐个取出字符并在相应位置插入空格
            for (int i = 0; i < ms.length(); i++) {
                temp += ms.charAt(i);
                if ((i + 1) % 4 == 0)
                    temp += " ";
            }
            bankCardAccount.removeTextChangedListener(addCardAccountListener);
            GameLog.log(temp);
            if(temp.substring(temp.length()-1).equals(" ")){
                temp = temp.substring(0,temp.length()-1);
            }
            bankCardAccount.setText(temp);
            bankCardAccount.setSelection(temp.length());
            bankCardAccount.addTextChangedListener(addCardAccountListener);
        }

        @Override
        public void onTextChanged(CharSequence s, int start, int before, int count) {
                /*String content = s.toString(); //1
                content = content.replaceAll("(?<=^\\d{3})"," ");
                content = content.replaceAll("(\\d{4})(?!$)","$1");
                GameLog.log(content);

                DecimalFormat df=new DecimalFormat("####,####,####,####"); //2
                BigDecimal mob=new BigDecimal(ms);
                String a=df.format(mob);
                String b=a.replace(",", " ");
                GameLog.log(b);*/
        }
    };

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        bankCardAccount.addTextChangedListener(addCardAccountListener);
        presenter.postBankList("","");
        /*String userName = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_ACCOUNT);
        String pwd = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_PWD);*/
    }


    @OnClick({R.id.bankCardSubmit, R.id.setClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.bankCardSubmit:
                DoubleClickHelper.getNewInstance().disabledView(bankCardSubmit);
                onCheckAndSubmit();
                break;
            case R.id.setClose:
                hide();
                break;
        }
    }

    private void onCheckAndSubmit() {
        String realName = bankCardRealName.getText().toString().trim();
        String account = bankCardAccount.getText().toString().trim().replace(" ","");
        String adds = bankCardAdds.getText().toString().trim();
        if(Check.isEmpty(realName)){
            showMessage("请输入真实姓名");
            return;
        }
        if(Check.isEmpty(account)){
            showMessage("请输入银行卡账号");
            return;
        }
        if(Check.isEmpty(adds)){
            showMessage("请输入开户行省份/城市");
            return;
        }
        presenter.postBindBank("",realName,account,adds,payId);
    }

    @Override
    public void postBankListResult(BankListResult bankListResult) {
        BankListResult.UserBankAccountBean userBankAccountBean = bankListResult.getUser_bank_account();
        if(!Check.isNull(userBankAccountBean)){
            GameLog.log("用户的银行卡信息是 "+userBankAccountBean.toString());
            if(!Check.isEmpty(userBankAccountBean.getAlias())){
                bankCardRealName.setText(userBankAccountBean.getAlias());
            }
            if(!Check.isEmpty(userBankAccountBean.getBank_Account())){
                bankCardAccount.setText(userBankAccountBean.getBank_Account());
            }
            if(!Check.isEmpty(userBankAccountBean.getBank_Address())){
                bankCardAdds.setText(userBankAccountBean.getBank_Address());
            }
        }

        final List<BankListResult.BankListBean> dataBeanList = bankListResult.getBank_list();
        SpinnerTextFormatter textFormatter = new SpinnerTextFormatter<BankListResult.BankListBean>() {
            @Override
            public Spannable format(BankListResult.BankListBean dataBean) {
                return new SpannableString(dataBean.getBankname());
            }
        };
        bankCardId.setSpinnerTextFormatter(textFormatter);
        bankCardId.setSelectedTextFormatter(textFormatter);
        bankCardId.setOnSpinnerItemSelectedListener(new OnSpinnerItemSelectedListener() {
            @Override
            public void onItemSelected(NiceSpinner parent, View view, int position, long id) {
                // This example uses String, but your type can be any
                BankListResult.BankListBean dataBean = (BankListResult.BankListBean)bankCardId.getSelectedItem();
                payId = dataBean.getId();
                bankCardId.setText(dataBean.getBankname());
                GameLog.log("绑定银行卡选中的是 " +dataBean.getBankname()+" payId:"+payId);
            }
        });
        bankCardId.attachDataSource(dataBeanList);
        payId = dataBeanList.get(0).getId();
        bankCardId.setText(dataBeanList.get(0).getBankname());
        if(!Check.isNull(userBankAccountBean)){
            if(!Check.isEmpty(userBankAccountBean.getBank_Name())){
                bankCardId.setText(userBankAccountBean.getBank_Name());
            }
        }
    }

    @Override
    public void postBindBankResult(BindCardResult bindCardResult) {
        ACache.get(getContext()).put("BindCardResult","true");
        EventBus.getDefault().post(new BindCardEvent());
        hide();
    }

    @Override
    public void setPresenter(BindCardContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }
}
