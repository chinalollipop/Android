package com.hgapp.a6668.homepage.cplist.quickbet.mothed;

import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.RadioButton;
import android.widget.RadioGroup;

import com.hgapp.a6668.CPInjections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseDialogFragment;
import com.hgapp.a6668.common.widgets.bottomdialog.NBaseBottomDialog;
import com.hgapp.a6668.data.CPQuickBetMothedResult;
import com.hgapp.a6668.data.CPQuickBetResult;
import com.hgapp.common.util.Check;

import butterknife.BindView;
import butterknife.OnClick;

/**
 * Created by Daniel on 2017/7/19.
 */

public class QuickBetMethodFragment extends HGBaseDialogFragment implements QuickBetMethodContract.View {
    private static final String TYPE1 = "type1";
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

    public QuickBetMethodFragment() {
    }

    public static QuickBetMethodFragment newInstance(CPQuickBetResult cpQuickBetResult) {
        QuickBetMethodFragment logoutFragment = new QuickBetMethodFragment();
        Bundle args = new Bundle();
        args.putParcelable(TYPE1, cpQuickBetResult);
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
        }
        radioGroupId.setOnCheckedChangeListener(new RadioGroup.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(RadioGroup radioGroup, int i) {
                switch (i) {
                    case R.id.quickBetMethod1:
                        showMessage("方式一");
                        break;
                    case R.id.quickBetMethod2:
                        showMessage("方式二");
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

    }

}
