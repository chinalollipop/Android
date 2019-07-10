package com.qpweb.a01.ui.home.agency;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;

import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;

import butterknife.OnClick;

public class AgencyScreenFragment extends BaseDialogFragment {


    public static AgencyScreenFragment newInstance() {
        Bundle bundle = new Bundle();
        AgencyScreenFragment loginFragment = new AgencyScreenFragment();
        loginFragment.setArguments(bundle);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.agency_screen_fragment;
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

    @OnClick({ R.id.agencyScreenClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.agencyScreenClose:
                hide();
                break;
        }
    }


}
