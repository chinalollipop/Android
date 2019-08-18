package com.qpweb.a01.ui.home.hongbao;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.utils.Check;

import org.greenrobot.eventbus.EventBus;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class HBaoNoticeFragment extends BaseDialogFragment {


    @BindView(R.id.hBaoNoticeMessage)
    TextView hBaoNoticeMessage;
    String message;
    public static HBaoNoticeFragment newInstance(String message) {
        Bundle bundle = new Bundle();
        bundle.putString("message",message);
        HBaoNoticeFragment loginFragment = new HBaoNoticeFragment();
        loginFragment.setArguments(bundle);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.hbao_notice_fragment;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            message = getArguments().getString("message");
        }
    }

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        if(!Check.isEmpty(message)){
            hBaoNoticeMessage.setText(message);
        }
    }

    @OnClick({R.id.hBaoNoticePlay, R.id.hBaoNoticeClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.hBaoNoticeClose:
                hide();
                break;
            case R.id.hBaoNoticePlay:
                EventBus.getDefault().post(new GoPlayEvent());
                hide();
                break;
        }
    }

}
