package com.hgapp.bet365.personpage.bindingcard;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.hgapp.bet365.Injections;
import com.hgapp.bet365.R;
import com.hgapp.bet365.base.HGBaseFragment;
import com.hgapp.bet365.base.IPresenter;
import com.hgapp.bet365.common.util.ACache;
import com.hgapp.bet365.common.util.HGConstant;
import com.hgapp.bet365.common.widgets.NTitleBar;
import com.hgapp.bet365.data.GetBankCardListResult;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.hgapp.bet365.homepage.handicap.ShowMainEvent;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class BindingCardFragment extends HGBaseFragment implements BindingCardContract.View {


    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    @BindView(R.id.tvBindingCardCBack)
    NTitleBar tvBindingCardCBack;
    @BindView(R.id.tvBindingCardTit)
    TextView tvBindingCardTit;
    @BindView(R.id.tvBindingCardAccountName)
    TextView tvBindingCardAccountName;
    @BindView(R.id.tvBindingCardBankName)
    TextView tvBindingCardBankName;
    @BindView(R.id.tvBindingCardBankCode)
    EditText tvBindingCardBankCode;
    @BindView(R.id.tvBindingCardBankState)
    TextView tvBindingCardBankState;
    @BindView(R.id.tvBindingCardBankAddress)
    EditText tvBindingCardBankAddress;
    @BindView(R.id.tvBindingCardTownsName)
    EditText tvBindingCardTownsName;
    @BindView(R.id.tvBindingCardPwd2)
    EditText tvBindingCardPwd2;
    @BindView(R.id.tvBindingCardPwd)
    EditText tvBindingCardPwd;
    @BindView(R.id.tvBindingCardSubmit)
    Button tvBindingCardSubmit;
    @BindView(R.id.llBindingCardPwd)
    LinearLayout llBindingCardPwd;
    @BindView(R.id.llBindingCardPwd2)
    LinearLayout llBindingCardPwd2;
    @BindView(R.id.tvBindingCardService)
    TextView tvBindingCardService;

    private String typeArgs1;
    private String typeArgs2;
    private BindingCardContract.Presenter presenter;
    OptionsPickerView optionsPickerViewBank;

    OptionsPickerView optionsPickerViewState;
    static  List<String> stateList  = new ArrayList<String>();
    static {
        stateList.add("安徽省");
        stateList.add("北京市");
        stateList.add("重庆市");
        stateList.add("广东省");
        stateList.add("湖南省");
        stateList.add("湖北市");
        stateList.add("海南省");
        stateList.add("江苏省");

    }

    public static BindingCardFragment newInstance(String type1, String type2) {
        BindingCardFragment fragment = new BindingCardFragment();
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
    public void setPresenter(BindingCardContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_bindingcard;
    }
    String bincCard;
    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        bincCard = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT+ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT)+HGConstant.USERNAME_BIND_CARD);
        if("1".equals(bincCard)){
            llBindingCardPwd.setVisibility(View.GONE);
            llBindingCardPwd2.setVisibility(View.GONE);
            tvBindingCardSubmit.setText("更改绑定");
            tvBindingCardTit.setText("银行卡更改");
        }else{
            tvBindingCardSubmit.setText("确认绑定");
            tvBindingCardTit.setText("银行卡绑定");
        }
        String aliasName = ACache.get(getContext()).getAsString(HGConstant.USERNAME_ALIAS);
        if(Check.isEmpty(aliasName)){
            tvBindingCardAccountName.setText("暂无");
        }else{
            String name = ""+aliasName.substring(0,1)+(aliasName.length()>=3?"**":"*");
            tvBindingCardAccountName.setText(name);
        }
        //tvBindingCardAccountName.setText(ACache.get(getContext()).getAsString(HGConstant.USERNAME_ALIAS));
        presenter.postGetBankCardList("","banks");
        tvBindingCardCBack.setMoreText(typeArgs1);
        tvBindingCardCBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                pop();
            }
        });

        optionsPickerViewState = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                tvBindingCardBankState.setText(stateList.get(options1));
            }
        }).build();
        optionsPickerViewState.setPicker(stateList);

    }


    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void postGetBankCardListResult(final GetBankCardListResult getBankCardListResult) {

        optionsPickerViewBank = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                tvBindingCardBankName.setText(getBankCardListResult.getData().get(options1));
            }
        }).build();
        optionsPickerViewBank.setPicker(getBankCardListResult.getData());

    }

    @Override
    public void postBindingBankCardResult(Object withdrawResult) {
        GameLog.log("绑定银行卡》》》》。");
        ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_ACCOUNT+ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT)+HGConstant.USERNAME_BIND_CARD,"1");
        finish();
    }


    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }


    private void onCheckWithdrawSubmit() {

        String bankName = tvBindingCardBankName.getText().toString().trim();
        String bankCode = tvBindingCardBankCode.getText().toString().trim();
        String bankAddress = tvBindingCardBankAddress.getText().toString().trim();
        String tvPwd = tvBindingCardPwd.getText().toString().trim();
        String tvPwd2 = tvBindingCardPwd2.getText().toString().trim();

        if(bankName.equals("请选择银行")){
            showMessage("请选择开户银行！");
            return;
        }
        if(Check.isEmpty(bankCode)){
            showMessage("请输入银行账户！");
            return;
        }

        if(Check.isEmpty(bankCode)){
            showMessage("请输入银行地址！");
            return;
        }

        if("1".equals(bincCard)){
            presenter.postBindingBankCard("","reset",bankName,bankCode,bankAddress,"","");
        }else{
            if(Check.isEmpty(tvPwd)||tvPwd.length()<6){
                showMessage("请输入有效密码！");
                return;
            }

            if(Check.isEmpty(tvPwd2)||tvPwd2.length()<6){
                showMessage("请输入有效确认密码！");
                return;
            }

            if(Check.isEmpty(tvPwd2)){
                showMessage("请输入确认提款密码！");
                return;
            }

            if(!tvPwd.equals(tvPwd2)){
                showMessage("2次输入密码不一致，请重新输入！");
                return;
            }
            presenter.postBindingBankCard("","bind",bankName,bankCode,bankAddress,tvPwd,tvPwd2);
        }





        /*presenter.postBindingBankCard("",tvBindingCardAddress.getText().toString(),tvBindingCardAccount.getText().toString(),tvBindingCardName.getText().toString(),
                money,pwd, ACache.get(getContext()).getAsString(HGConstant.USERNAME_ALIAS),"Y");*/

    }

    @Override
    public void onVisible() {
        super.onVisible();
    }


    @OnClick({R.id.tvBindingCardService,R.id.tvBindingCardBankName, R.id.tvBindingCardBankState,R.id.tvBindingCardSubmit})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.tvBindingCardService:
                //EventBus.getDefault().post(new StartBrotherEvent(ServiceOnlineFragment.newInstance(), SupportFragment.SINGLETASK));
                finish();
                EventBus.getDefault().post(new ShowMainEvent(3));
                break;
            case R.id.tvBindingCardBankName:
                hideKeyboard();
                optionsPickerViewBank.show();
                break;
            case R.id.tvBindingCardBankState:
                optionsPickerViewState.show();
                break;
            case R.id.tvBindingCardSubmit:
                onCheckWithdrawSubmit();
                break;
        }
    }

}