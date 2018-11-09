package com.hgapp.a0086.personpage.accountcenter;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.FragmentTransaction;
import android.view.View;
import android.widget.FrameLayout;
import android.widget.TextView;

import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseFragment;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.common.widgets.NTitleBar;
import com.hgapp.a0086.data.BetRecordResult;
import com.hgapp.a0086.personpage.managepwd.ManagePwdFragment;
import com.hgapp.common.util.GameLog;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class AccountCenterFragment extends HGBaseFragment implements AccountCenterContract.View {

    private static final String TYPE = "type";
    @BindView(R.id.backTitleAccountCenter)
    NTitleBar backTitleAccountCenter;
    @BindView(R.id.btnAccountCenterMyInform)
    TextView btnAccountCenterMyInform;
    @BindView(R.id.btnAccountCenterChangePwd)
    TextView btnAccountCenterChangePwd;
    @BindView(R.id.fragementAccountCenterChange)
    FrameLayout fragementId;
    private AccountCenterContract.Presenter presenter;

    private String typeArgs;

    public static AccountCenterFragment newInstance(String type) {
        AccountCenterFragment fragment = new AccountCenterFragment();
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
        return R.layout.fragment_account_center;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {

        /*if ("today".equals(typeArgs)) {
            presenter.postBetToday("", "FT", "0");
        } else {
            presenter.postBetHistory("", "FT", "0");
        }*/
        backTitleAccountCenter.setMoreText(typeArgs);
        backTitleAccountCenter.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                pop();
            }
        });
        btnAccountCenterMyInform.performClick();
        /*ManagePwdFragment  managePwdFragment = ManagePwdFragment.newInstance();
        FragmentTransaction ft = getFragmentManager().beginTransaction().replace(R.id.fragementAccountCenterChange, managePwdFragment);
        ft.show(managePwdFragment);
        *//*if(visible)
        {
            ft.show(balanceFragment);
        }
        else
        {
            ft.hide(balanceFragment);
        }*//*
        ft.commit();*/
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


    @OnClick({R.id.btnAccountCenterMyInform, R.id.btnAccountCenterChangePwd})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.btnAccountCenterMyInform:
                AccountCenterMainFragment accountCenterＭainFragment = AccountCenterMainFragment.newInstance("");
                FragmentTransaction ft = getFragmentManager().beginTransaction().replace(R.id.fragementAccountCenterChange, accountCenterＭainFragment);
                ft.show(accountCenterＭainFragment);
                ft.commit();
                btnAccountCenterMyInform.setTextColor(getResources().getColor(R.color.title_text));
                //tvBetRecordToday.setBackgroundResource(R.drawable.bg_btn_focus);
                btnAccountCenterMyInform.setBackground(getResources().getDrawable(R.mipmap.account_center_pwd_high));
                btnAccountCenterChangePwd.setTextColor(getResources().getColor(R.color.n_edittext_hint));
                btnAccountCenterChangePwd.setBackground(getResources().getDrawable(R.mipmap.account_center_pwd_normal));
                break;
            case R.id.btnAccountCenterChangePwd:
                btnAccountCenterMyInform.setTextColor(getResources().getColor(R.color.n_edittext_hint));
                btnAccountCenterMyInform.setBackground(getResources().getDrawable(R.mipmap.account_center_pwd_normal));
                btnAccountCenterChangePwd.setTextColor(getResources().getColor(R.color.title_text));
                btnAccountCenterChangePwd.setBackground(getResources().getDrawable(R.mipmap.account_center_pwd_high));
                ManagePwdFragment  managePwdFragment = ManagePwdFragment.newInstance();
                FragmentTransaction ft2 = getFragmentManager().beginTransaction().replace(R.id.fragementAccountCenterChange, managePwdFragment);
                ft2.show(managePwdFragment);
                ft2.commit();
                break;
        }
    }
}
