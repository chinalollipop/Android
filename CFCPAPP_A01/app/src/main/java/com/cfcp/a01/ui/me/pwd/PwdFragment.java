package com.cfcp.a01.ui.me.pwd;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.design.widget.TabLayout;
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
import com.cfcp.a01.common.utils.DateHelper;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.TeamReportResult;

import java.text.SimpleDateFormat;
import java.util.Arrays;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class PwdFragment extends BaseFragment implements PwdContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.pwdBack)
    NTitleBar pwdBack;
    @BindView(R.id.pwdTab)
    TabLayout pwdTab;
    @BindView(R.id.pwdOldLogin)
    EditText pwdOldLogin;
    @BindView(R.id.pwdFundText)
    TextView pwdFundText;
    @BindView(R.id.pwdNewLogin)
    EditText pwdNewLogin;
    @BindView(R.id.pwdNew2Login)
    EditText pwdNew2Login;
    @BindView(R.id.pwdSubmit)
    TextView pwdSubmit;
    @BindView(R.id.pwdReset)
    TextView pwdReset;

    private String typeArgs2, typeArgs3;
    PwdContract.Presenter presenter;
    //代表彩种ID
    private String lotteryId = "1";
    String startTime, endTime;
    //1已经设置过资金密码; 0 没有设置过资金密码
    String fundPwd;
    int position;
    public static PwdFragment newInstance(String deposit_mode, String money) {
        PwdFragment betFragment = new PwdFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, deposit_mode);
        args.putString(TYPE3, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_pwd;
    }

    //标记为红色
    private String onMarkRed(String sign) {
        return " <font color='#e13f51'>" + sign + "</font>";
    }

    private void initPwdStyle() {
        //presenter.getDepositSubmit(typeArgs2,"","","");
        pwdTab.addTab(pwdTab.newTab().setText("修改登录密码"));
        pwdTab.addTab(pwdTab.newTab().setText("设置资金密码"));
        pwdTab.addOnTabSelectedListener(new TabLayout.OnTabSelectedListener() {
            @Override
            public void onTabSelected(TabLayout.Tab tab) {
                position = tab.getPosition();
                switch (position) {
                    case 0:
                        pwdBack.setTitle("修改登录密码");
                        pwdOldLogin.setVisibility(View.VISIBLE);
                        pwdFundText.setVisibility(View.GONE);
                        pwdOldLogin.setHint("请输入旧登录密码");
                        pwdNewLogin.setHint("请输入新登录密码");
                        pwdNew2Login.setHint("确认新登录密码");
                        pwdSubmit.setText("修 改");
                        break;
                    case 1:
                        pwdBack.setTitle("设置资金密码");
                        if (!Check.isEmpty(fundPwd)&&"1".equals(fundPwd)) {
                            pwdOldLogin.setHint("输入旧资金密码");
                            pwdOldLogin.setVisibility(View.VISIBLE);
                            pwdFundText.setVisibility(View.GONE);
                        }else{
                            pwdOldLogin.setVisibility(View.GONE);
                            pwdFundText.setVisibility(View.VISIBLE);
                        }
                        pwdNewLogin.setHint("输入新资金密码");
                        pwdNew2Login.setHint("确认新资金密码");
                        pwdSubmit.setText("提 交");
                        break;
                }
            }

            @Override
            public void onTabUnselected(TabLayout.Tab tab) {
            }

            @Override
            public void onTabReselected(TabLayout.Tab tab) {
            }
        });
    }


    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            typeArgs2 = getArguments().getString(TYPE2);
            typeArgs3 = getArguments().getString(TYPE3);
        }
    }

    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
        return format.format(date);
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        fundPwd = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_FUND_PWD);
        initPwdStyle();
        startTime = DateHelper.getToday();
        endTime = DateHelper.getTom();
        if (typeArgs2.equals("1")) {
            pwdBack.setTitle("修改登录密码");
            pwdFundText.setVisibility(View.GONE);
        } else {
            pwdBack.setTitle("设置资金密码");
            //pwdTab.getChildAt(1).setSelected(true);
            pwdTab.getTabAt(1).select();
            //pwdTab.getTabAt(1).getCustomView().setSelected(true);
        }
        pwdBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });

    }


    //请求数据接口
    private void onRequsetData() {
        String oldLogin = pwdOldLogin.getText().toString().trim();
        String pwdNew = pwdNewLogin.getText().toString().trim();
        String pwdNew2 = pwdNew2Login.getText().toString().trim();

        switch (position){
            case 0:
                if(Check.isEmpty(oldLogin)){
                    showMessage("请输入旧登录密码");
                    return;
                }
                if(Check.isEmpty(pwdNew)){
                    showMessage("请输入新登录密码");
                    return;
                }
                if(Check.isEmpty(pwdNew2)){
                    showMessage("请输入确认登录密码");
                    return;
                }
                if(!pwdNew.equals(pwdNew2)){
                    showMessage("新登录密码和确认密码不一致");
                    return;
                }
                if(oldLogin.equals(pwdNew2)){
                    showMessage("新登录密码不能和旧登录密码一致");
                    return;
                }
                presenter.getChangeLoginPwd(oldLogin, pwdNew2);

                break;
            case 1:
                if(!Check.isEmpty(fundPwd)&&fundPwd.equals("1")){
                    if(Check.isEmpty(oldLogin)){
                        showMessage("请输入旧资金密码");
                        return;
                    }
                    if(Check.isEmpty(pwdNew)){
                        showMessage("请输入新资金密码");
                        return;
                    }
                    if(Check.isEmpty(pwdNew2)){
                        showMessage("请输入确认资金密码");
                        return;
                    }
                    if(!pwdNew.equals(pwdNew2)){
                        showMessage("新资金密码和确认资金密码不一致");
                        return;
                    }
                    if(oldLogin.equals(pwdNew2)){
                        showMessage("新资金密码不能和旧资金密码一致");
                        return;
                    }
                    presenter.getChangeFundPwd(oldLogin, pwdNew2);
                }else{
                    if(Check.isEmpty(pwdNew)){
                        showMessage("请输入新资金密码");
                        return;
                    }
                    if(Check.isEmpty(pwdNew2)){
                        showMessage("请输入确认资金密码");
                        return;
                    }
                    if(!pwdNew.equals(pwdNew2)){
                        showMessage("新资金密码和确认资金密码不一致");
                        return;
                    }
                    presenter.getChangeFundPwdFirst(pwdNew, pwdNew2);
                }
                break;
        }

    }

    @Override
    public void getChangeFundPwdResult(TeamReportResult teamReportResult) {
        GameLog.log("团队区间报表 成功");
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_FUND_PWD,"1");
        showMessage("修改资金密码成功！");
        finish();
    }

    @Override
    public void getChangeLoginPwdResult(TeamReportResult teamReportResult) {
        showMessage("修改登录密码成功！");
        finish();
    }

    @Override
    public void setPresenter(PwdContract.Presenter presenter) {
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


    @OnClick({R.id.pwdSubmit, R.id.pwdReset})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.pwdSubmit:
                onRequsetData();
                break;
            case R.id.pwdReset:
                pwdOldLogin.setText("");
                pwdNewLogin.setText("");
                pwdNew2Login.setText("");
                break;
        }
    }
}
