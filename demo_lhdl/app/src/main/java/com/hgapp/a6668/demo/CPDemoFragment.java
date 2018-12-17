package com.hgapp.a6668.demo;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.hgapp.a6668.CPInjections;
import com.hgapp.a6668.HGApplication;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.BaseActivity2;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.widgets.CPBottomBar;
import com.hgapp.a6668.common.widgets.MarqueeTextView;
import com.hgapp.a6668.data.CPNoteResult;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import me.jessyan.retrofiturlmanager.RetrofitUrlManager;

public class CPDemoFragment extends BaseActivity2 implements CPDemoContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.dummy_button1)
    Button dummyButton1;
    @BindView(R.id.dummy_button2)
    Button dummyButton2;
    @BindView(R.id.fullscreen_content_controls)
    LinearLayout fullscreenContentControls;
    private String userName, userMoney, fshowtype, M_League, getArgParam4, fromType;
    CPDemoContract.Presenter presenter;
    private String agMoney, hgMoney;
    private String titleName = "";
    private String dzTitileName = "";

    @Override
    public void onCreate(Bundle savedInstanceState) {
        CPInjections.inject(this, null);
        super.onCreate(savedInstanceState);
        /*if (getArguments() != null) {
            userName = getArguments().getStringArrayList(ARG_PARAM1).get(0);
            userMoney = getArguments().getStringArrayList(ARG_PARAM1).get(1);
            fshowtype = getArguments().getStringArrayList(ARG_PARAM1).get(2);// 用以判断是电子还是真人
        }*/
    }

    @Override
    public int setLayoutId() {
        return R.layout.activity_democp;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        //StatusBarUtil.setColor(this, getResources().getColor(R.color.cp_status_bar));
//            StatusBarUtil.setTranslucentForImageView(this,cpListTitle);
        // RetrofitUrlManager.getInstance().putDomain("CpUrl", "http://mc.hg01455.com/");
        /*cpBottomBar.postDelayed(new Runnable() {
            @Override
            public void run() {
                presenter.postCPInit();
            }
        },10000);*/
        String cpUrl = getIntent().getStringExtra("urlCp");
        String urlLogin = getIntent().getStringExtra("urlLogin");
        RetrofitUrlManager.getInstance().putDomain("CpUrl", cpUrl);
    }

    @Override
    public void postCPNoteResult(CPNoteResult cpNoteResult) {

        if (!Check.isNull(cpNoteResult)) {
            ACache.get(getContext()).put(HGConstant.USERNAME_CP_HOME_NOTICE, JSON.toJSONString(cpNoteResult));
            List<String> stringList = new ArrayList<String>();
            int size = cpNoteResult.getData().size();
            for (int i = 0; i < size; ++i) {
                stringList.add(cpNoteResult.getData().get(i).getComment());
            }
            if (stringList.size() == 1) {
                stringList.add(cpNoteResult.getData().get(0).getComment());
            }
            GameLog.log("服务器的公告");
        }
    }


    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void setPresenter(CPDemoContract.Presenter presenter) {

        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    @Override
    public void onDestroy() {
        super.onDestroy();
        EventBus.getDefault().unregister(this);
    }

    @OnClick({R.id.dummy_button1, R.id.dummy_button2})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.dummy_button1:
                presenter.postCPLogin(ACache.get(getContext()).getAsString(HGConstant.USERNAME_CP_INFORM));
                break;
            case R.id.dummy_button2:
                presenter.postCPInit();
                break;
        }
    }
}
