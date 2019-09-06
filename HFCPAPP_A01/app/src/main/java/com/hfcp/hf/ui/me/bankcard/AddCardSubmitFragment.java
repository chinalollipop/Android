package com.hfcp.hf.ui.me.bankcard;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.TextView;

import com.hfcp.hf.Injections;
import com.hfcp.hf.R;
import com.hfcp.hf.common.base.BaseFragment;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.utils.GameLog;
import com.hfcp.hf.common.widget.NTitleBar;
import com.hfcp.hf.data.BankCardAddResult;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class AddCardSubmitFragment extends BaseFragment implements AddCardSubmitContract.View {

    private static final String TYPE4 = "type4";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.addCardBack)
    NTitleBar addCardBack;
    @BindView(R.id.addCardBankName)
    TextView addCardBankName;
    @BindView(R.id.addCardName)
    TextView addCardName;
    @BindView(R.id.addCardAccountName)
    TextView addCardAccountName;
    @BindView(R.id.addCardAccount)
    TextView addCardAccount;
    @BindView(R.id.addCardSubmit)
    TextView addCardSubmit;
    @BindView(R.id.addCardReset)
    TextView addCardReset;
    private String typeArgs4, typeArgs3;
    AddCardSubmitContract.Presenter presenter;
    private int  bankId;
    BankCardAddResult bankCardAddResult;

    /**
     *
     * @param bankCardAddResult
     * @param type 空为添加 1为 修改
     * @return
     */
    public static AddCardSubmitFragment newInstance(BankCardAddResult bankCardAddResult, String type,String id) {
        AddCardSubmitFragment addCardFragment = new AddCardSubmitFragment();
        Bundle args = new Bundle();
        args.putParcelable(TYPE2, bankCardAddResult);
        args.putString(TYPE3, type);
        args.putString(TYPE4, id);
        addCardFragment.setArguments(args);
        Injections.inject(addCardFragment, null);
        return addCardFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_add_card_submit;
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            bankCardAddResult = getArguments().getParcelable(TYPE2);
            typeArgs3 = getArguments().getString(TYPE3);
            typeArgs4 = getArguments().getString(TYPE4);
        }
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {

        addCardBankName.setText(bankCardAddResult.getBank());
        addCardName.setText(bankCardAddResult.getBranch());
        addCardAccountName.setText(bankCardAddResult.getAccount_name());
        String ms = bankCardAddResult.getAccount();
        String temp = "";
        //逐个取出字符并在相应位置插入空格
        for (int i = 0; i < ms.length(); i++) {
            temp += ms.charAt(i);
            if ((i + 1) % 4 == 0)
                temp += " ";
        }
        addCardAccount.setText(temp);
        addCardBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
    }

    //请求数据接口
    private void onRequsetData() {
        presenter.getAddCardSubmit(typeArgs3,typeArgs4,bankCardAddResult.getBank(),bankCardAddResult.getBank_id(),bankCardAddResult.getBranch(),bankCardAddResult.getAccount_name(),bankCardAddResult.getAccount(),bankCardAddResult.getAccount_confirmation());
    }

    @Override
    public void getAddCardSubmitResult(BankCardAddResult bankCardAddResult) {
        if(typeArgs4.equals("0")){
            showMessage("添加银行卡成功！");
            popTo(CardFragment.class,false);
        }else{
            GameLog.log("clear session");
            presenter.getModifyCardClearSession(typeArgs4);
        }

    }

    @Override
    public void getModifyCardClearSession() {
        showMessage("添加银行卡成功！");
        popTo(CardFragment.class,false);
    }

    @Override
    public void setPresenter(AddCardSubmitContract.Presenter presenter) {
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


    @OnClick({R.id.addCardSubmit, R.id.addCardReset})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.addCardSubmit:
                onRequsetData();
                break;
            case R.id.addCardReset:
                finish();
                break;
        }
    }


}
