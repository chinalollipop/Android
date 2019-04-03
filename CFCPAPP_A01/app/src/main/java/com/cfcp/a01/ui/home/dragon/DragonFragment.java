package com.cfcp.a01.ui.home.dragon;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.design.widget.TabLayout;
import android.view.View;
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
import butterknife.OnClick;

public class DragonFragment extends BaseFragment implements DragonContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.dragonBack)
    NTitleBar dragonBack;
    @BindView(R.id.dragonTab)
    TabLayout dragonTab;

    private String typeArgs2, typeArgs3;
    DragonContract.Presenter presenter;
    //代表彩种ID
    private String lotteryId = "1";
    String startTime, endTime;
    //1已经设置过资金密码; 0 没有设置过资金密码
    String fundPwd;
    int position;
    public static DragonFragment newInstance(String deposit_mode, String money) {
        DragonFragment betFragment = new DragonFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, deposit_mode);
        args.putString(TYPE3, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_dragon;
    }

    //标记为红色
    private String onMarkRed(String sign) {
        return " <font color='#e13f51'>" + sign + "</font>";
    }

    private void initPwdStyle() {
        //presenter.getDepositSubmit(typeArgs2,"","","");
        dragonTab.addTab(dragonTab.newTab().setText("最新长龙"));
        dragonTab.addTab(dragonTab.newTab().setText("我的投注"));
        dragonTab.addOnTabSelectedListener(new TabLayout.OnTabSelectedListener() {
            @Override
            public void onTabSelected(TabLayout.Tab tab) {
                position = tab.getPosition();
                switch (position) {
                    case 0:
                        break;
                    case 1:
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
        dragonBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });

    }


    //请求数据接口
    private void onRequsetData() {

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
    public void setPresenter(DragonContract.Presenter presenter) {
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


}
