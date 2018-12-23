package com.hgapp.a0086.homepage.cplist.quickbet.mothed;

import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.RadioButton;
import android.widget.RadioGroup;

import com.hgapp.a0086.CPInjections;
import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseDialogFragment;
import com.hgapp.a0086.data.CPQuickBetMothedResult;
import com.hgapp.a0086.data.CPQuickBetResult;
import com.hgapp.a0086.homepage.cplist.events.QuickBetMothedEvent;
import com.hgapp.a0086.homepage.cplist.quickbet.QuickBetParam;

import org.greenrobot.eventbus.EventBus;

import butterknife.BindView;
import butterknife.OnClick;

/**
 * Created by Daniel on 2017/7/19.
 */

public class QuickBetMethodFragment extends HGBaseDialogFragment implements QuickBetMethodContract.View {
    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    @BindView(R.id.radioGroupId)
    RadioGroup radioGroupId;
    @BindView(R.id.quickBetMethod1)
    RadioButton quickBetMethod1;
    @BindView(R.id.quickBetMethod2)
    RadioButton quickBetMethod2;
    @BindView(R.id.betMethodCpCancel)
    Button betMethodCpCancel;
    @BindView(R.id.betMethodCpSubmit)
    Button betMethodCpSubmit;
    private QuickBetMethodContract.Presenter presenter;
    CPQuickBetResult cpQuickBetResult;
    QuickBetParam quickBetParam;
    String sort = "1";
    public QuickBetMethodFragment() {
    }

    public static QuickBetMethodFragment newInstance(CPQuickBetResult cpQuickBetResult,QuickBetParam quickBetParam) {
        QuickBetMethodFragment logoutFragment = new QuickBetMethodFragment();
        Bundle args = new Bundle();
        args.putParcelable(TYPE1, cpQuickBetResult);
        args.putParcelable(TYPE2,quickBetParam);
        logoutFragment.setArguments(args);
        CPInjections.inject((QuickBetMethodContract.View) logoutFragment, null);
        return logoutFragment;
    }

    @OnClick({R.id.betMethodCpCancel, R.id.betMethodCpSubmit})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.betMethodCpCancel:
                dismiss();
                break;
            case R.id.betMethodCpSubmit:
                presenter.postQuickBetMothed(quickBetParam.code,quickBetParam.game_code,quickBetParam.code_number,sort,quickBetParam.token);
                break;
        }

    }

    @Override
    public void setPresenter(QuickBetMethodContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    public void setStart(int action) {

    }

    @Override
    public void onStop() {
        super.onStop();
        if (null != presenter) {
            presenter.destroy();
        }
    }

    @Override
    protected int getLayoutResId() {
        return R.layout.layout_quick_bet_mothed;
    }

    @Override
    protected void initView(View view, Bundle bundle) {
        if (null != getArguments()) {
            cpQuickBetResult = getArguments().getParcelable(TYPE1);
            quickBetParam =  getArguments().getParcelable(TYPE2);
        }
        radioGroupId.setOnCheckedChangeListener(new RadioGroup.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(RadioGroup radioGroup, int i) {
                switch (i) {
                    case R.id.quickBetMethod1:
                        showMessage("方式一");
                        sort = "1";
                        break;
                    case R.id.quickBetMethod2:
                        showMessage("方式二");
                        sort = "2";
                        break;
                }
            }
        });
    }

    @Override
    public void setError(int action, int errcode) {
    }

    @Override
    public void setError(int action, String errString) {
    }

    @Override
    public void setComplete(int action) {
        dismiss();
    }

    @Override
    public void postQuickBetMothedResult(CPQuickBetMothedResult cpQuickBetMothedResult) {
        if(cpQuickBetMothedResult.getCode()==200){
            EventBus.getDefault().post(new QuickBetMothedEvent("0"));
            dismiss();
        }
        showMessage(cpQuickBetMothedResult.getMsg());
    }

}
