package com.qpweb.a01.ui.home.imme;

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
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.QPConstant;

import org.greenrobot.eventbus.EventBus;

import java.util.Random;

import butterknife.BindView;
import butterknife.OnClick;

public class ImmeFragment extends BaseDialogFragment {

    int postion = 1;

    int gameMusic = 1;
    int bgMusic = 1;

    public static ImmeFragment newInstance() {
        Bundle bundle = new Bundle();
        ImmeFragment loginFragment = new ImmeFragment();
        loginFragment.setArguments(bundle);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.imme_fragment;
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

    @OnClick({R.id.imme_getNow, R.id.immeClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.imme_getNow:
                BindFragment.newInstance().show(getFragmentManager());
                hide();
                break;
            case R.id.immeClose:
                hide();
                break;
        }
    }


}
