package com.qpweb.a01.ui.loginhome.sign;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.data.ChangeAccountEvent;
import com.qpweb.a01.ui.home.bind.BindFragment;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.QPConstant;

import org.greenrobot.eventbus.EventBus;

import butterknife.BindView;
import butterknife.OnClick;

public class SignHelpFragment extends BaseDialogFragment {

    @BindView(R.id.signHelpClose)
    ImageView signHelpClose;

    public static SignHelpFragment newInstance() {
        Bundle bundle = new Bundle();
        SignHelpFragment loginFragment = new SignHelpFragment();
        loginFragment.setArguments(bundle);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.sign_help_fragment;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
        }

    }

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
    }

    @OnClick({R.id.signHelpClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.signHelpClose:
                hide();
                break;
        }
    }
}
