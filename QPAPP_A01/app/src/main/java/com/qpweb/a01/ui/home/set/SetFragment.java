package com.qpweb.a01.ui.home.set;

import android.content.Context;
import android.content.Intent;
import android.media.AudioManager;
import android.media.MediaPlayer;
import android.media.SoundPool;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageSwitcher;
import android.widget.ImageView;
import android.widget.TextView;

import com.qpweb.a01.LaunchActivity;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.data.ChangeAccountEvent;
import com.qpweb.a01.data.MusicBgEvent;
import com.qpweb.a01.ui.home.bind.BindFragment;
import com.qpweb.a01.ui.loginhome.LoginHomeActivity;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.QPConstant;
import com.qpweb.a01.utils.Utils;

import org.greenrobot.eventbus.EventBus;

import java.util.HashMap;
import java.util.Random;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class SetFragment extends BaseDialogFragment {

    int postion = 1;
    @BindView(R.id.setGameMusic)
    ImageView setGameMusic;
    @BindView(R.id.setBgMusic)
    ImageView setBgMusic;
    @BindView(R.id.setBindPhone)
    TextView setBindPhone;
    @BindView(R.id.setChangeAccount)
    TextView setChangeAccount;
    @BindView(R.id.setClose)
    ImageView setClose;

    int gameMusic = 1;
    int bgMusic = 1;

    public static SetFragment newInstance() {
        Bundle bundle = new Bundle();
        SetFragment loginFragment = new SetFragment();
        loginFragment.setArguments(bundle);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.set_fragment;
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

    @OnClick({R.id.setWithDrawPwd,R.id.setGameMusic, R.id.setBgMusic, R.id.setBindPhone, R.id.setChangeAccount, R.id.setClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.setWithDrawPwd:
                SetPwdFragment.newInstance().show(getFragmentManager());
                break;
            case R.id.setGameMusic:
                if(gameMusic==0){
                    gameMusic =1;
                    setGameMusic.setBackground(getResources().getDrawable(R.mipmap.switch_open));
                }else{
                    gameMusic = 0;
                    setGameMusic.setBackground(getResources().getDrawable(R.mipmap.switch_close));
                }
                break;
            case R.id.setBgMusic:
                if(bgMusic==0){
                    bgMusic =1;
                    int dex = new Random().nextInt(4);
                    if(dex==0){
                        dex =1;
                    }
                    EventBus.getDefault().post(new MusicBgEvent(dex));
                    setBgMusic.setBackground(getResources().getDrawable(R.mipmap.switch_open));
                }else{
                    bgMusic = 0;
                    EventBus.getDefault().post(new MusicBgEvent(0));
                    setBgMusic.setBackground(getResources().getDrawable(R.mipmap.switch_close));
                }
                break;
            case R.id.setBindPhone:
                BindFragment.newInstance().show(getFragmentManager());
                break;
            case R.id.setChangeAccount:
                ACache.get(getContext()).put("isChangeUser","YES");
                EventBus.getDefault().post(new ChangeAccountEvent());
               /* getActivity().finish();
                Intent intent = new Intent(getContext(), LaunchActivity.class);
                startActivity(intent);*/
                break;
            case R.id.setClose:
                hide();
                break;
        }
    }


}
