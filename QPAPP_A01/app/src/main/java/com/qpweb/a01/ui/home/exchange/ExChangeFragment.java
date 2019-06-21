package com.qpweb.a01.ui.home.exchange;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.QPConstant;
import com.xw.repo.BubbleSeekBar;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class ExChangeFragment extends BaseDialogFragment {

    int postion = 1;
    int gameMusic = 1;
    int bgMusic = 1;
    @BindView(R.id.exMoney)
    TextView exMoney;
    @BindView(R.id.exRecord)
    TextView exRecord;
    @BindView(R.id.exNumber)
    EditText exNumber;
    @BindView(R.id.exMax)
    TextView exMax;
    @BindView(R.id.exBankNumber)
    TextView exBankNumber;
    @BindView(R.id.exChangeBank)
    TextView exChangeBank;
    @BindView(R.id.exSeekBar)
    BubbleSeekBar exSeekBar;
    @BindView(R.id.exSubmit)
    TextView exSubmit;
    @BindView(R.id.exClose)
    ImageView exClose;

    public static ExChangeFragment newInstance() {
        Bundle bundle = new Bundle();
        ExChangeFragment loginFragment = new ExChangeFragment();
        loginFragment.setArguments(bundle);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.exchange_fragment;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
        }

    }

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        String userName = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_ACCOUNT);
        String pwd = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_PWD);
    }




    @OnClick({R.id.exRecord, R.id.exMax, R.id.exChangeBank, R.id.exSubmit,R.id.exClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.exRecord:
                break;
            case R.id.exMax:
                break;
            case R.id.exChangeBank:
                break;
            case R.id.exSubmit:
                break;
            case R.id.exClose:
                hide();
                break;
        }
    }
}
