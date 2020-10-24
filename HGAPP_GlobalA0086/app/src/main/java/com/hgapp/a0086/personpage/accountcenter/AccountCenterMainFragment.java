package com.hgapp.a0086.personpage.accountcenter;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseFragment;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.common.util.ACache;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.data.BetRecordResult;
import com.hgapp.a0086.data.LoginResult;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.Unbinder;

public class AccountCenterMainFragment extends HGBaseFragment implements AccountCenterContract.View {

    private static final String TYPE = "type";
    @BindView(R.id.accountName)
    TextView accountName;
    @BindView(R.id.accountAlias)
    TextView accountAlias;
    @BindView(R.id.accountPhone)
    TextView accountPhone;
    @BindView(R.id.accountBirthday)
    TextView accountBirthday;
    @BindView(R.id.accountWechat)
    TextView accountWechat;
    Unbinder unbinder;
    private AccountCenterContract.Presenter presenter;

    private String typeArgs;

    public static AccountCenterMainFragment newInstance(String type) {
        AccountCenterMainFragment fragment = new AccountCenterMainFragment();
        Bundle args = new Bundle();
        args.putString(TYPE, type);
        fragment.setArguments(args);
        //Injections.inject(null, fragment);
        return fragment;
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            typeArgs = getArguments().getString(TYPE);
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_account_center_main;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {

        LoginResult loginResult = JSON.parseObject(ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_INFO), LoginResult.class);
        accountName.setText(loginResult.getUserName());
        if(Check.isEmpty(loginResult.getAlias())){
            accountAlias.setText(loginResult.getAlias());
        }else{
            String name = ""+loginResult.getAlias().substring(0,1)+(loginResult.getAlias().length()>=3?"**":"*");
            accountAlias.setText(name);
        }
        accountPhone.setText(loginResult.getPhone());
        accountBirthday.setText(loginResult.getBirthday());
        accountWechat.setText(loginResult.getE_Mail());
        GameLog.log(loginResult.toString());
    }

    @Override
    public void postBetRecordResult(BetRecordResult message) {
        GameLog.log("总共充值多少：" + message.getTotal());

    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    @Override
    public void setPresenter(AccountCenterContract.Presenter presenter) {

        this.presenter = presenter;
    }

}
