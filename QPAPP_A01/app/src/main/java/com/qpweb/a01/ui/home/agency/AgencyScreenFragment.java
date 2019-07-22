package com.qpweb.a01.ui.home.agency;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.ImageView;

import com.bumptech.glide.Glide;
import com.bumptech.glide.request.RequestOptions;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.GameLog;

import butterknife.BindView;
import butterknife.OnClick;

public class AgencyScreenFragment extends BaseDialogFragment {
    @BindView(R.id.agencyScreenQC)
    ImageView agencyScreenQC;

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
        String urlQc = ACache.get(getContext()).getAsString("promotion_qrcode_link");
        GameLog.log("用户二维码的地址 "+urlQc);
        Glide.with(AgencyScreenFragment.this).load(urlQc).apply(new RequestOptions().fitCenter()).into(agencyScreenQC);
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
