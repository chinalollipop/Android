package com.hgapp.a6668.homepage.handicap;

import android.support.v4.app.FragmentActivity;
import android.util.TypedValue;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.FrameLayout;
import android.widget.LinearLayout;

import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.homepage.handicap.leaguedetail.zhbet.ZHBetViewManager;
import com.hgapp.common.util.GameLog;

import org.greenrobot.eventbus.EventBus;

public class BottombarViewManager {
    private volatile static BottombarViewManager singleton;
    LinearLayout bottomBar1,bottomBar2,bottomBar3,bottomBar4;
    FrameLayout mFloatBtnWrapper;
    FrameLayout content;
    String form;

    private BottombarViewManager(){}
    public static BottombarViewManager getSingleton() {
        if (singleton == null) {
            synchronized (BottombarViewManager.class) {
                if (singleton == null) {
                    singleton = new BottombarViewManager();
                }
            }
        }
        return singleton;
    }

    public void onShowView(final FragmentActivity context, final HGBaseFragment fragment, final String form, final String userMoney, final String active){
        this.form = form;
        GameLog.log("============================mFloatBtnWrapper============================"+mFloatBtnWrapper);
        if(mFloatBtnWrapper==null){
            mFloatBtnWrapper= (FrameLayout) LayoutInflater.from(fragment.getContext()).inflate(R.layout.fragment_bottombar,null,false);
            //stackView = (ImageView) mFloatBtnWrapper.findViewById(R.id.iv_shine);
            bottomBar1 = (LinearLayout) mFloatBtnWrapper.findViewById(R.id.bottomBar1);
            bottomBar2 = (LinearLayout) mFloatBtnWrapper.findViewById(R.id.bottomBar2);
            bottomBar3 = (LinearLayout) mFloatBtnWrapper.findViewById(R.id.bottomBar3);
            bottomBar4 = (LinearLayout) mFloatBtnWrapper.findViewById(R.id.bottomBar4);
            View root = fragment.getActivity().findViewById(android.R.id.content);
            if (root instanceof FrameLayout) {
                content = (FrameLayout) root;
                FrameLayout.LayoutParams params = new FrameLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT);
                params.gravity = Gravity.BOTTOM;
                final int dp18 = (int) TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, 18, fragment.getResources().getDisplayMetrics());
                params.topMargin = dp18 * 7;
                params.rightMargin = 0;
                params.bottomMargin = 0;
                mFloatBtnWrapper.setLayoutParams(params);
                content.addView(mFloatBtnWrapper);
                //mFloatBtnWrapper.setOnTouchListener(new StackViewTouchListener(mFloatBtnWrapper, dp18 / 4));
                bottomBar1.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        onCloseView();
                        EventBus.getDefault().post(new ShowMainEvent(2));
                        fragment.popTo(HandicapFragment.class,true);
                    }
                });
                bottomBar2.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        onCloseView();
                        EventBus.getDefault().post(new ShowMainEvent(1));
                        fragment.popTo(HandicapFragment.class,true);
                    }
                });
                bottomBar3.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        onCloseView();
                        EventBus.getDefault().post(new ShowMainEvent(3));
                        fragment.popTo(HandicapFragment.class,true);
                    }
                });
                bottomBar4.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        onCloseView();
                        EventBus.getDefault().post(new ShowMainEvent(4));
                        fragment.popTo(HandicapFragment.class,true);
                    }
                });
            }
        }
        //ZHBetManager.getSingleton().onClearData();
    }

    public void onCloseView(){
        if(null!=content&&null!=mFloatBtnWrapper){
            content.removeView(mFloatBtnWrapper);
            mFloatBtnWrapper = null;
            ZHBetViewManager.getSingleton().onCloseView();
            GameLog.log("============================mFloatBtnWrapper=null==========================="+mFloatBtnWrapper);
        }
    }

}
