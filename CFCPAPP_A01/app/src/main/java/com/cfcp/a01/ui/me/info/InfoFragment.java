package com.cfcp.a01.ui.me.info;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.TextView;

import com.cfcp.a01.CFConstant;
import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.data.LowerInfoDataResult;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class InfoFragment extends BaseFragment implements InfoContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.infoBack)
    NTitleBar infoBack;
    @BindView(R.id.infoNick)
    TextView infoNick;
    @BindView(R.id.infoName)
    TextView infoName;
    @BindView(R.id.infoPhone)
    TextView infoPhone;
    @BindView(R.id.infoAccount)
    EditText infoAccount;
    @BindView(R.id.infoAccountText)
    TextView infoAccountText;
    @BindView(R.id.infoEmail)
    TextView infoEmail;
    @BindView(R.id.infoQQ)
    TextView infoQQ;
    @BindView(R.id.infoSubmit)
    TextView infoSubmit;
    Unbinder unbinder;
    private String typeArgs2, typeArgs3;
    InfoContract.Presenter presenter;

    public static InfoFragment newInstance(String user_id, String money) {
        InfoFragment betFragment = new InfoFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, user_id);
        args.putString(TYPE3, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_info;
    }

    //标记为红色
    private String onMarkRed(String sign) {
        return " <font color='#e13f51'>" + sign + "</font>";
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
        infoBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        if(Check.isEmpty(typeArgs2)){
            String name =  ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_NAME);
            if(Check.isEmpty(name)){
                infoAccount.setText("");
                infoAccount.requestFocus();
                infoAccount.setEnabled(true);
            }else{
                infoAccount.setText(name);
                infoAccount.setClickable(false);
                infoAccount.setEnabled(false);
                infoAccountText.setVisibility(View.GONE);
                infoSubmit.setVisibility(View.GONE);
            }
            String  phone ,qq, email;
            phone = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_PHONE);
            qq =  ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_QQ);
            email = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_EMAIL);
            if(Check.isEmpty(phone)){
                phone = "暂无";
            }
            if(Check.isEmpty(qq)){
                qq = "暂无";
            }
            if(Check.isEmpty(email)){
                email = "暂无";
            }
            infoPhone.setText(phone);
            infoName.setText(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_ACCOUNT));
            infoNick.setText(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_NICK));
            infoQQ.setText(qq);
            infoEmail.setText(email);
        }else{
            presenter.getLowerLevelReport(typeArgs2);
            infoAccount.setClickable(false);
            infoAccount.setEnabled(false);
            infoAccount.setText("");
            infoAccount.setHint("");
            infoAccountText.setVisibility(View.GONE);
            infoSubmit.setVisibility(View.GONE);
        }

    }


    //请求数据接口
    private void onRequsetData() {
        String name = infoAccount.getText().toString().trim();
        if (Check.isEmpty(name)) {
            showMessage("请填写真实姓名");
            return;
        }
        presenter.getRealName("", name, "", "");
    }

    @Override
    public void getRealNameResult(LoginResult loginResult) {
        //转账前渠道确认
        GameLog.log("设置真实姓名 成功");
        showMessage("设置用户信息成功！");
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_NAME,infoAccount.getText().toString().trim());
        finish();
    }

    @Override
    public void getLowerLevelReportResult(LowerInfoDataResult lowerInfoDataResult) {
        GameLog.log("获取下级的信息 ");
        String mobile,name,nickname,qq,email;
        mobile = lowerInfoDataResult.getMobile();
        name = lowerInfoDataResult.getName();
        nickname = lowerInfoDataResult.getNickname();
        qq = lowerInfoDataResult.getQq();
        email = lowerInfoDataResult.getEmail();
        if(Check.isEmpty(mobile)){
            mobile = "暂无";
        }
        if(Check.isEmpty(qq)){
            qq = "暂无";
        }
        if(Check.isEmpty(email)){
            email = "暂无";
        }
        if(Check.isEmpty(name)){
            name = "暂无";
        }
        if(Check.isEmpty(nickname)){
            nickname = "暂无";
        }
        infoPhone.setText(mobile);
        infoName.setText(lowerInfoDataResult.getUsername());
        infoAccount.setText(name);
        infoNick.setText(nickname);
        infoQQ.setText(qq);
        infoEmail.setText(email);
    }

    @Override
    public void setPresenter(InfoContract.Presenter presenter) {
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

    @OnClick(R.id.infoSubmit)
    public void onViewClicked() {
        onRequsetData();
    }
}
