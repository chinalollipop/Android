package com.cfcp.a01.ui.me.userlist.setprize;

import android.os.Bundle;
import android.support.annotation.Nullable;
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
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.data.LowerInfoDataResult;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import butterknife.Unbinder;

public class SetPrizeFragment extends BaseFragment implements SetPrizeContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.setPrizeBack)
    NTitleBar setPrizeBack;
    @BindView(R.id.setPrizeNick)
    TextView setPrizeNick;
    @BindView(R.id.setPrizeName)
    TextView setPrizeName;
    @BindView(R.id.setPrizePhone)
    TextView setPrizePhone;
    @BindView(R.id.setPrizeAccountText)
    TextView setPrizeAccountText;
    @BindView(R.id.setPrizeEmail)
    TextView setPrizeEmail;
    @BindView(R.id.setPrizeSubmit)
    TextView setPrizeSubmit;
    Unbinder unbinder;
    private String typeArgs2, typeArgs3;
    SetPrizeContract.Presenter presenter;

    public static SetPrizeFragment newInstance(String user_id, String money) {
        SetPrizeFragment betFragment = new SetPrizeFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, user_id);
        args.putString(TYPE3, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_setprize;
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
        setPrizeBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        presenter.getLowerLevelReport(typeArgs2);
    }

    //请求数据接口
    private void onRequsetData() {
        presenter.getRealName("", "", "", "");
    }

    @Override
    public void getRealNameResult(LoginResult loginResult) {
        //转账前渠道确认
        GameLog.log("设置真实姓名 成功");


    }

    @Override
    public void getLowerLevelReportResult(LowerInfoDataResult lowersetPrizeDataResult) {
        GameLog.log("获取下级的信息 ");
        setPrizePhone.setText(lowersetPrizeDataResult.getMobile());
        setPrizeName.setText(lowersetPrizeDataResult.getName());
        setPrizeNick.setText(lowersetPrizeDataResult.getNickname());
        setPrizeEmail.setText(lowersetPrizeDataResult.getEmail());
    }

    @Override
    public void setPresenter(SetPrizeContract.Presenter presenter) {
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

    @OnClick(R.id.setPrizeSubmit)
    public void onViewClicked() {
        onRequsetData();
    }
}
