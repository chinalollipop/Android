package com.hgapp.m8.personpage.accountcenter;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.hgapp.common.util.Check;
import com.hgapp.m8.R;
import com.hgapp.m8.base.HGBaseFragment;
import com.hgapp.m8.base.IPresenter;
import com.hgapp.m8.common.util.ACache;
import com.hgapp.m8.common.util.HGConstant;
import com.hgapp.m8.common.widgets.NTitleBar;
import com.hgapp.m8.data.BetRecordResult;
import com.hgapp.m8.data.LoginResult;
import com.hgapp.m8.personpage.managepwd.ManagePwdFragment;
import com.hgapp.common.util.GameLog;
import com.hgapp.m8.personpage.realname.RealNameFragment;

import org.greenrobot.eventbus.EventBus;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class AccountCenterFragment extends HGBaseFragment implements AccountCenterContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    @BindView(R.id.backTitleAccountCenter)
    NTitleBar backTitleAccountCenter;
    @BindView(R.id.accountName)
    TextView accountName;
    @BindView(R.id.personJoinDays)
    TextView personJoinDays;
    @BindView(R.id.accountAlias)
    TextView accountAlias;
    @BindView(R.id.accountPhone)
    TextView accountPhone;
    @BindView(R.id.accountBirthday)
    TextView accountBirthday;
    @BindView(R.id.accountWechat)
    TextView accountWechat;
    private AccountCenterContract.Presenter presenter;

    private String typeArgs,johnDay;

    public static AccountCenterFragment newInstance(String type1,String type2) {
        AccountCenterFragment fragment = new AccountCenterFragment();
        Bundle args = new Bundle();
        args.putString(TYPE1, type1);
        args.putString(TYPE2, type2);
        fragment.setArguments(args);
        //Injections.inject(null, fragment);
        return fragment;
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            typeArgs = getArguments().getString(TYPE1);
            johnDay = getArguments().getString(TYPE2);
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_account_center;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        personJoinDays.setText(johnDay+"天");
        backTitleAccountCenter.setMoreText(typeArgs);
        backTitleAccountCenter.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                pop();
            }
        });
        LoginResult loginResult = JSON.parseObject(ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_INFO), LoginResult.class);
        accountName.setText(loginResult.getUserName());
        if(Check.isEmpty(loginResult.getAlias())){
            //accountAlias.setText(loginResult.getAlias());
        }else {
            String name = "" + loginResult.getAlias().substring(0, 1) + (loginResult.getAlias().length() >= 3 ? "**" : "*");
            accountAlias.setText(name);
        }
        if(!Check.isEmpty(loginResult.getPhone())){
            accountPhone.setText(loginResult.getPhone());
        }
        if(!Check.isEmpty(loginResult.getBirthday())){
            accountBirthday.setText(loginResult.getBirthday());
        }
        if(!Check.isEmpty(loginResult.getE_Mail())){
            accountWechat.setText(loginResult.getE_Mail());
        }

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

    @OnClick({R.id.btnAccountCenterChangePwd,R.id.accountAlias,R.id.accountPhone,R.id.accountWechat,R.id.accountBirthday})
    public void onViewClicked(View view) {
        switch (view.getId()){
           case R.id.btnAccountCenterChangePwd:
               EventBus.getDefault().post(new StartBrotherEvent(ManagePwdFragment.newInstance()));
               break;
            case R.id.accountAlias:
                if(accountAlias.getText().toString().equals("未绑定 >")){
                    finish();
                    EventBus.getDefault().post(new StartBrotherEvent(RealNameFragment.newInstance(typeArgs,""), SupportFragment.SINGLETASK));
                }
                break;
            case R.id.accountPhone:
                if(accountPhone.getText().toString().equals("未绑定 >")){
                    finish();
                    EventBus.getDefault().post(new StartBrotherEvent(RealNameFragment.newInstance(typeArgs,""), SupportFragment.SINGLETASK));
                }
                break;
            case R.id.accountWechat:
                if(accountWechat.getText().toString().equals("未绑定 >")){
                    finish();
                    EventBus.getDefault().post(new StartBrotherEvent(RealNameFragment.newInstance(typeArgs,""), SupportFragment.SINGLETASK));
                }
                break;
            case R.id.accountBirthday:
                if(accountBirthday.getText().toString().equals("未绑定 >")){
                    finish();
                    EventBus.getDefault().post(new StartBrotherEvent(RealNameFragment.newInstance(typeArgs,""), SupportFragment.SINGLETASK));
                }
                break;
        }

    }
}
