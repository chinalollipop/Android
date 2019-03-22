package com.cfcp.a01.ui.me.bankcard;

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
import com.cfcp.a01.data.BankCardListResult;
import com.cfcp.a01.data.BankListResult;
import com.cfcp.a01.data.TeamReportResult;

import org.greenrobot.eventbus.EventBus;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class ModifyCardFragment extends BaseFragment implements ModifyCardContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    BankCardListResult.ABankCardsBean aBankCardsBean;
    @BindView(R.id.modifyCardBack)
    NTitleBar modifyCardBack;
    @BindView(R.id.modifyCardName)
    TextView modifyCardName;
    @BindView(R.id.modifyCardBranch)
    EditText modifyCardBranch;
    @BindView(R.id.modifyCardAccount)
    EditText modifyCardAccount;
    @BindView(R.id.modifyCardNumber)
    EditText modifyCardNumber;
    @BindView(R.id.modifyCardNumber2)
    EditText modifyCardNumber2;
    @BindView(R.id.modifyCardNext)
    TextView modifyCardNext;
    Unbinder unbinder;
    private List<BankListResult.ABanksBean> aBanksBeans = new ArrayList<>();
    //type类型选择器
    OptionsPickerView typeOptionsPicker;
    private String typeArgs2, typeArgs3;
    ModifyCardContract.Presenter presenter;
    int position;
    int bankId;

    public static ModifyCardFragment newInstance(BankCardListResult.ABankCardsBean aBankCardsBean, String id) {
        ModifyCardFragment betFragment = new ModifyCardFragment();
        Bundle args = new Bundle();
        args.putParcelable(TYPE2, aBankCardsBean);
        args.putString(TYPE3, id);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_modify_card;
    }

    //标记为红色
    private String onMarkRed(String sign) {
        return " <font color='#e13f51'>" + sign + "</font>";
    }


    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            aBankCardsBean = getArguments().getParcelable(TYPE2);
            typeArgs3 = getArguments().getString(TYPE3);
        }
    }

    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
        return format.format(date);
    }


    TextWatcher modifyCardNumberListener = new TextWatcher() {
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
            modifyCardNumber.removeTextChangedListener(modifyCardNumberListener);
            GameLog.log(temp);
            if(temp.substring(temp.length()-1).equals(" ")){
                temp = temp.substring(0,temp.length()-1);
            }
            modifyCardNumber.setText(temp);
            modifyCardNumber.setSelection(temp.length());
            modifyCardNumber.addTextChangedListener(modifyCardNumberListener);
        }

        @Override
        public void onTextChanged(CharSequence s, int start, int before, int count) {
        }
    };

    TextWatcher modifyCardNumber2Listener = new TextWatcher() {
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
            modifyCardNumber2.removeTextChangedListener(modifyCardNumber2Listener);
            GameLog.log(temp);
            if(temp.substring(temp.length()-1).equals(" ")){
                temp = temp.substring(0,temp.length()-1);
            }
            modifyCardNumber2.setText(temp);
            modifyCardNumber2.setSelection(temp.length());
            modifyCardNumber2.addTextChangedListener(modifyCardNumber2Listener);
        }

        @Override
        public void onTextChanged(CharSequence s, int start, int before, int count) {
        }
    };


    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {

        bankId = aBankCardsBean.getBank_id();
        presenter.getBankList(aBankCardsBean.getId()+"");
        modifyCardName.setText(aBankCardsBean.getBank());
        modifyCardBranch.setText(aBankCardsBean.getBranch());
        modifyCardAccount.setText(aBankCardsBean.getAccount_name());
        String ms = aBankCardsBean.getAccount();
        String temp = "";
        //逐个取出字符并在相应位置插入空格
        for (int i = 0; i < ms.length(); i++) {
            temp += ms.charAt(i);
            if ((i + 1) % 4 == 0)
                temp += " ";
        }
        modifyCardNumber.setText(temp);
        modifyCardNumber.addTextChangedListener(modifyCardNumberListener);
        modifyCardNumber2.addTextChangedListener(modifyCardNumber2Listener);
        modifyCardBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        typeOptionsPicker = new OptionsPickerBuilder(getContext(), new OnOptionsSelectListener() {

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                //1、tab做相应的切换
                // 2、下面做查询数据的请求和展示
                String text = aBanksBeans.get(options1).getName();
                modifyCardName.setText(text);
                bankId = aBanksBeans.get(options1).getId();
            }
        }).build();
    }


    //请求数据接口
    private void onRequsetData() {

        String bank = modifyCardName.getText().toString().trim();
//      String bank,String bank_id,String branch,String account_name,String account,String account_confirmation
        String branch = modifyCardBranch.getText().toString().trim();
        String account_name = modifyCardAccount.getText().toString().trim();
        String account = modifyCardNumber.getText().toString().trim();
        String account_confirmation = modifyCardNumber2.getText().toString().trim();

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

        presenter.getModifyCard(typeArgs3+"",bank,bankId+"",branch,account_name,account,account_confirmation);
    }

    @Override
    public void getBankListResult(BankListResult bankListResult) {
        aBanksBeans = bankListResult.getABanks();
        typeOptionsPicker.setPicker(aBanksBeans);
    }

    @Override
    public void getModifyCardResult(BankCardAddResult bankCardAddResult) {
        EventBus.getDefault().post(new StartBrotherEvent(AddCardSubmitFragment.newInstance(bankCardAddResult,"1",typeArgs3+"")));
    }

    @Override
    public void setPresenter(ModifyCardContract.Presenter presenter) {
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

    @OnClick({R.id.modifyCardNext,R.id.modifyCardName})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.modifyCardName:
                    typeOptionsPicker.show();
                    break;
            case R.id.modifyCardNext:
                onRequsetData();
                break;
        }
    }
}
