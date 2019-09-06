package com.cfcp.a01.ui.me.bankcard;

import android.icu.math.BigDecimal;
import android.icu.text.DecimalFormat;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.event.StartBrotherEvent;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.BankCardAddResult;
import com.cfcp.a01.data.BankListResult;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.ui.me.pwd.PwdFragment;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class AddCardFragment extends BaseFragment implements AddCardContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.addCardBack)
    NTitleBar addCardBack;
    @BindView(R.id.addCardBankName)
    TextView addCardBankName;
    @BindView(R.id.addCardName)
    EditText addCardName;
    @BindView(R.id.addCardAccountName)
    EditText addCardAccountName;
    @BindView(R.id.addCardAccount)
    EditText addCardAccount;
    @BindView(R.id.addCardAccount2)
    EditText addCardAccount2;
    @BindView(R.id.addCardSubmit)
    TextView addCardSubmit;
    @BindView(R.id.addCardReset)
    TextView addCardReset;
    private String typeArgs2, typeArgs3;
    AddCardContract.Presenter presenter;
    private int  bankId;
    private List<BankListResult.ABanksBean> aBanksBeans  = new ArrayList<>();

    //type类型选择器
    OptionsPickerView typeOptionsPicker;
    public static AddCardFragment newInstance(String deposit_mode, String money) {
        AddCardFragment addCardFragment = new AddCardFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, deposit_mode);
        args.putString(TYPE3, money);
        addCardFragment.setArguments(args);
        Injections.inject(addCardFragment, null);
        return addCardFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_add_card;
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            typeArgs2 = getArguments().getString(TYPE2);
            typeArgs3 = getArguments().getString(TYPE3);
        }
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        if(Check.isNull(presenter)){
            presenter = Injections.inject(this, null);
        }
        presenter.getBankList();
        addCardBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        addCardAccount.addTextChangedListener(addCardAccountListener);
        addCardAccount2.addTextChangedListener(addCardAccount2Listener);
    }




    //请求数据接口
    private void onRequsetData() {

        String bank = addCardBankName.getText().toString().trim();
//      String bank,String bank_id,String branch,String account_name,String account,String account_confirmation
        String branch = addCardName.getText().toString().trim();
        String account_name = addCardAccountName.getText().toString().trim();
        String account = addCardAccount.getText().toString().trim();
        String account_confirmation = addCardAccount2.getText().toString().trim();
        if (Check.isEmpty(branch)) {
            showMessage("请输入支行名称");
            return;
        }
        if (Check.isEmpty(account_name)) {
            showMessage("请输入开户人姓名");
            return;
        }
        if (Check.isEmpty(account)) {
            showMessage("请输入银行账号");
            return;
        }
        if (Check.isEmpty(account_confirmation)) {
            showMessage("请输入确认银行账号");
            return;
        }
        if(!account.equals(account_confirmation)){
            showMessage("银行账号和确认银行账号不一致");
            return;
        }
        presenter.getAddCard(bank,bankId+"",branch,account_name,account,account_confirmation);

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
            addCardAccount.removeTextChangedListener(addCardAccountListener);
            GameLog.log(temp);
            if(temp.substring(temp.length()-1).equals(" ")){
                temp = temp.substring(0,temp.length()-1);
            }
            addCardAccount.setText(temp);
            addCardAccount.setSelection(temp.length());
            addCardAccount.addTextChangedListener(addCardAccountListener);
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

    TextWatcher addCardAccount2Listener = new TextWatcher() {
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
            addCardAccount2.removeTextChangedListener(addCardAccount2Listener);
            GameLog.log(temp);
            if(temp.substring(temp.length()-1).equals(" ")){
                temp = temp.substring(0,temp.length()-1);
            }
            addCardAccount2.setText(temp);
            addCardAccount2.setSelection(temp.length());
            addCardAccount2.addTextChangedListener(addCardAccount2Listener);
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
    public void getBankListResult(BankListResult bankListResult) {
        //转账前渠道确认
        GameLog.log("设置真实姓名 成功");
        aBanksBeans=  bankListResult.getABanks();
        typeOptionsPicker = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                //1、tab做相应的切换
                // 2、下面做查询数据的请求和展示
                String text = aBanksBeans.get(options1).getName();
                addCardBankName.setText(text);
                bankId = aBanksBeans.get(options1).getId();
            }
        }).build();
        typeOptionsPicker.setPicker(aBanksBeans);
        //默认展示第一个
        String text = aBanksBeans.get(0).getName();
        addCardBankName.setText(text);
        bankId = aBanksBeans.get(0).getId();
    }

    @Override
    public void getAddCardResult(BankCardAddResult bankCardAddResult) {
        GameLog.log("设置真实姓名2 成功");
        EventBus.getDefault().post(new StartBrotherEvent(AddCardSubmitFragment.newInstance(bankCardAddResult,"","0")));
    }

    @Override
    public void getFundPwdResult(String message) {
        showMessage(message);
        pop();
        EventBus.getDefault().post(new StartBrotherEvent(PwdFragment.newInstance("2","")));
        GameLog.log("没有设置资金密码");
    }

    @Override
    public void setPresenter(AddCardContract.Presenter presenter) {
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


    @OnClick({R.id.addCardBankName, R.id.addCardSubmit, R.id.addCardReset})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.addCardBankName:
                hideKeyboard();
                if(!Check.isNull(typeOptionsPicker)) {
                    typeOptionsPicker.show();
                }
                break;
            case R.id.addCardSubmit:
                onRequsetData();
                break;
            case R.id.addCardReset:
                addCardAccountName.setText("");
                addCardAccount.setText("");
                addCardAccount.setText("");
                break;
        }
    }
}
