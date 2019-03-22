package com.cfcp.a01.ui.me.bankcard;

import android.os.Bundle;
import android.os.Parcelable;
import android.support.annotation.Nullable;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;

import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.event.StartBrotherEvent;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.BankCardListResult;
import com.cfcp.a01.data.TeamReportResult;

import org.greenrobot.eventbus.EventBus;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class ModifyFragment extends BaseFragment implements ModifyContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.cardModifyBack)
    NTitleBar cardModifyBack;
    @BindView(R.id.cardModifyAccount)
    TextView cardModifyAccount;
    @BindView(R.id.cardModifyUserName)
    EditText cardModifyUserName;
    @BindView(R.id.cardModifyBankAccount)
    EditText cardModifyBankAccount;
    @BindView(R.id.cardModifyFundPwd)
    EditText cardModifyFundPwd;
    @BindView(R.id.cardModifySubmit)
    TextView cardModifySubmit;
    @BindView(R.id.cardModifyReset)
    TextView cardModifyReset;
    BankCardListResult.ABankCardsBean aBankCardsBean;
    private String typeArgs2, typeArgs3;
    ModifyContract.Presenter presenter;
    int position=-1;
    ArrayList<BankCardListResult.ABankCardsBean> aBankCardsBeans;

    public static ModifyFragment newInstance(BankCardListResult.ABankCardsBean aBankCardsBean, String type, List<BankCardListResult.ABankCardsBean> aBankCardsBeans) {
        ModifyFragment betFragment = new ModifyFragment();
        Bundle args = new Bundle();
        args.putParcelableArrayList(TYPE1, (ArrayList<? extends Parcelable>) aBankCardsBeans);
        args.putParcelable(TYPE2, aBankCardsBean);
        args.putString(TYPE3, type);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_modify;
    }

    //标记为红色
    private String onMarkRed(String sign) {
        return " <font color='#e13f51'>" + sign + "</font>";
    }


    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            aBankCardsBeans =  getArguments().getParcelableArrayList(TYPE1);
            aBankCardsBean = getArguments().getParcelable(TYPE2);
            typeArgs3 = getArguments().getString(TYPE3);
        }
    }

    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
        return format.format(date);
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        if(!Check.isNull(aBankCardsBean)){
            cardModifyAccount.setText("卡号：**** **** **** "+aBankCardsBean.getAccount().substring(aBankCardsBean.getAccount().length()-4));
            cardModifyUserName.setText(aBankCardsBean.getAccount_name());
        }
        cardModifyBankAccount.addTextChangedListener(addCardAccountListener);
        cardModifyBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        if (typeArgs3.equals("1")) {
            cardModifyBack.setTitle("修改银行卡");
            cardModifySubmit.setText("下一步");
            cardModifyReset.setText("重 置");
        } else if(typeArgs3.equals("2")){
            cardModifyBack.setTitle("删除银行卡");
            cardModifySubmit.setText("删 除");
            cardModifyReset.setText("取 消");
        }else if(typeArgs3.equals("3")){
            cardModifyBack.setTitle("验证银行卡");
            cardModifySubmit.setText("下一步");
            cardModifyReset.setText("重 置");
            cardModifyAccount.setVisibility(View.GONE);
            cardModifyUserName.setText("");
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
            cardModifyBankAccount.removeTextChangedListener(addCardAccountListener);
            GameLog.log(temp);
            if(temp.substring(temp.length()-1).equals(" ")){
                temp = temp.substring(0,temp.length()-1);
            }
            cardModifyBankAccount.setText(temp);
            cardModifyBankAccount.setSelection(temp.length());
            cardModifyBankAccount.addTextChangedListener(addCardAccountListener);
        }

        @Override
        public void onTextChanged(CharSequence s, int start, int before, int count) {
        }
    };


    //请求数据接口
    private void onRequsetData() {
        String cardUserName = cardModifyUserName.getText().toString().trim();
        String cardAccount = cardModifyBankAccount.getText().toString().trim();
        String cardFundPwd = cardModifyFundPwd.getText().toString().trim();
        if (Check.isEmpty(cardUserName)) {
            showMessage("请输入开户人姓名");
            return;
        }
        if (Check.isEmpty(cardAccount)) {
            showMessage("请输入银行账号");
            return;
        }
        if (Check.isEmpty(cardFundPwd)) {
            showMessage("请输入资金密码");
            return;
        }
        if (typeArgs3.equals("1")) {
            presenter.getCardModify(aBankCardsBean.getId()+"",cardUserName,cardAccount, cardFundPwd);
        } else if (typeArgs3.equals("2")) {
            presenter.getCardDelete(aBankCardsBean.getId()+"",cardUserName,cardAccount, cardFundPwd);
        }else{
            for(int k=0;k<aBankCardsBeans.size();++k){
                if(cardAccount.replace(" ","").equals(aBankCardsBeans.get(k).getAccount())){
                    position = aBankCardsBeans.get(k).getId();
                }
            }
            if(position==-1){
                showMessage("银行卡信息有误");
                return;
            }
            presenter.getCardVerify(position+"",cardUserName,cardAccount, cardFundPwd);
        }
    }

    @Override
    public void getCardModifyResult(TeamReportResult teamReportResult) {
        GameLog.log("修改银行卡1 成功"+aBankCardsBean.getId());
        EventBus.getDefault().post(new StartBrotherEvent(ModifyCardFragment.newInstance(aBankCardsBean,aBankCardsBean.getId()+"")));
    }

    @Override
    public void getCardVerifyResult(TeamReportResult teamReportResult) {
        GameLog.log("验证银行卡1 成功");
        EventBus.getDefault().post(new StartBrotherEvent(AddCardFragment.newInstance("1","")));
    }

    @Override
    public void getCardDeleteResult(TeamReportResult teamReportResult) {
        showMessage("删除银行卡成功！");
        finish();
    }

    @Override
    public void setPresenter(ModifyContract.Presenter presenter) {
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

    @OnClick({R.id.cardModifySubmit, R.id.cardModifyReset})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.cardModifySubmit:
                onRequsetData();
                break;
            case R.id.cardModifyReset:
                if (typeArgs3.equals("1")) {
                    cardModifyUserName.setText("");
                    cardModifyBankAccount.setText("");
                    cardModifyFundPwd.setText("");
                }else{
                    finish();
                }

                break;
        }
    }
}
