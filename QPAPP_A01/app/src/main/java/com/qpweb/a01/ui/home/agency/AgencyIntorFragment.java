package com.qpweb.a01.ui.home.agency;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.data.ChangeAccountEvent;
import com.qpweb.a01.data.MusicBgEvent;
import com.qpweb.a01.ui.home.bind.BindFragment;
import com.qpweb.a01.ui.home.set.SetPwdFragment;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.QPConstant;

import org.greenrobot.eventbus.EventBus;

import java.util.Random;

import butterknife.BindView;
import butterknife.OnClick;

public class AgencyIntorFragment extends BaseDialogFragment {


    public static AgencyIntorFragment newInstance() {
        Bundle bundle = new Bundle();
        AgencyIntorFragment loginFragment = new AgencyIntorFragment();
        loginFragment.setArguments(bundle);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.agency_infor_fragment;
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

    @OnClick({ R.id.agencyInforClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.agencyInforClose:
                hide();
                break;
        }
    }


}
