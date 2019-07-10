package com.qpweb.a01.ui.home.hongbao;

import android.os.Bundle;
import android.os.CountDownTimer;
import android.support.annotation.Nullable;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;
import com.qpweb.a01.Injections;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.data.ValidResult;
import com.qpweb.a01.ui.home.RefreshMoneyEvent;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.GameLog;
import com.qpweb.a01.utils.QPConstant;
import com.qpweb.a01.utils.TimeHelper;
import com.qpweb.a01.widget.MarqueeTextView;

import org.greenrobot.eventbus.EventBus;

import java.math.RoundingMode;
import java.text.DecimalFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class HBaoNoticeFragment extends BaseDialogFragment  {




    public static HBaoNoticeFragment newInstance() {
        Bundle bundle = new Bundle();
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

        }
    }

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
    }

    @OnClick({ R.id.hBaoNoticePlay,R.id.hBaoNoticeClose})
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
