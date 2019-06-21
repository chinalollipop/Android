package com.qpweb.a01.ui.home.notice;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.data.ChangeAccountEvent;
import com.qpweb.a01.data.MusicBgEvent;
import com.qpweb.a01.data.NoticeResult;
import com.qpweb.a01.ui.home.bind.BindFragment;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.QPConstant;

import org.greenrobot.eventbus.EventBus;

import java.util.List;
import java.util.Random;

import butterknife.BindView;
import butterknife.OnClick;

public class NoticeFragment extends BaseDialogFragment {

    @BindView(R.id.noticeClose)
    ImageView noticeClose;
    @BindView(R.id.noticeTView)
    TextView noticeTView;

    int gameMusic = 1;
    int bgMusic = 1;

    public static NoticeFragment newInstance() {
        Bundle bundle = new Bundle();
        NoticeFragment loginFragment = new NoticeFragment();
        loginFragment.setArguments(bundle);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.notice_fragment;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
        }
    }

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        /*List<NoticeResult> noticeResult = JSON.parseArray(ACache.get(getContext()).getAsString("QP_Notice"),NoticeResult.class);
        int size = noticeResult.size();
        for(int k=0;k<size;++k){
            noticeTView.setText(noticeResult.get(k).getTitle()+"\n"+noticeResult.get(k).getContent());
        }*/
        NoticeResult noticeResult = JSON.parseObject(ACache.get(getContext()).getAsString("QP_Notice"),NoticeResult.class);
        noticeTView.setText(""+noticeResult.getTitle()+"\n\n"+noticeResult.getContent());
    }

    @OnClick({ R.id.noticeClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.noticeClose:
                hide();
                break;
        }
    }


}
