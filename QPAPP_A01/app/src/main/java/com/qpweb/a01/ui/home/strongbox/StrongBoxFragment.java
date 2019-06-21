package com.qpweb.a01.ui.home.strongbox;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
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

public class StrongBoxFragment extends BaseDialogFragment {

    int postion = 1;
    @BindView(R.id.boxClose)
    ImageView boxClose;

    int gameMusic = 1;
    int bgMusic = 1;
    @BindView(R.id.boxIn)
    TextView boxIn;
    @BindView(R.id.boxOut)
    TextView boxOut;
    @BindView(R.id.boxSeekBar)
    BubbleSeekBar boxSeekBar;
    @BindView(R.id.boxMax)
    TextView boxMax;
    @BindView(R.id.boxTViewType)
    TextView boxTViewType;
    @BindView(R.id.boxSure)
    TextView boxSure;

    public static StrongBoxFragment newInstance() {
        Bundle bundle = new Bundle();
        StrongBoxFragment loginFragment = new StrongBoxFragment();
        loginFragment.setArguments(bundle);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.strong_box_fragment;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
        }

    }

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        boxSeekBar.getConfigBuilder().min(1).max(1).build();
        boxSeekBar.setProgress(boxSeekBar.getMax());
        String userName = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_ACCOUNT);
        String pwd = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_PWD);
    }

    @OnClick({R.id.boxIn, R.id.boxOut, R.id.boxMax, R.id.boxSure,R.id.boxClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.boxIn:
                boxTViewType.setText("存入数量：");
                boxIn.setBackgroundResource(R.mipmap.box_in);
                boxOut.setBackgroundResource(R.mipmap.box_out);
                break;
            case R.id.boxOut:
                boxIn.setBackgroundResource(R.mipmap.box_out);
                boxOut.setBackgroundResource(R.mipmap.box_in);
                boxTViewType.setText("取出数量：");
                break;
            case R.id.boxMax:
                break;
            case R.id.boxSure:
                break;
            case R.id.boxClose:
                hide();
                break;
        }
    }
}
