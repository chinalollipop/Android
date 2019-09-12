package com.vene.tian.homepage.handicap.leaguedetail.zhbet;

import android.support.v4.app.FragmentActivity;
import android.util.TypedValue;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.FrameLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.vene.tian.R;
import com.vene.tian.base.HGBaseFragment;
import com.vene.tian.homepage.sportslist.bet.BetOrderZHSubmitDialog;
import com.vene.common.util.GameLog;

public class ZHBetViewManager {
    private volatile static ZHBetViewManager singleton;

    TextView floatNumber;
    RelativeLayout mFloatBtnWrapper;
    FrameLayout content;
    String form;

    private ZHBetViewManager(){}
    public static ZHBetViewManager getSingleton() {
        if (singleton == null) {
            synchronized (ZHBetViewManager.class) {
                if (singleton == null) {
                    singleton = new ZHBetViewManager();
                }
            }
        }
        return singleton;
    }

    public void onShowView(final FragmentActivity context, final HGBaseFragment fragment, final String form, final String userMoney, final String active){
        this.form = form;
        GameLog.log("============================mFloatBtnWrapper============================"+mFloatBtnWrapper);
        if(mFloatBtnWrapper==null){
            mFloatBtnWrapper= (RelativeLayout) LayoutInflater.from(fragment.getContext()).inflate(R.layout.float_btn,null,false);
            //stackView = (ImageView) mFloatBtnWrapper.findViewById(R.id.iv_shine);
            floatNumber = (TextView) mFloatBtnWrapper.findViewById(R.id.floatNumber);
            View root = fragment.getActivity().findViewById(android.R.id.content);
            if (root instanceof FrameLayout) {
                content = (FrameLayout) root;
                FrameLayout.LayoutParams params = new FrameLayout.LayoutParams(ViewGroup.LayoutParams.WRAP_CONTENT, ViewGroup.LayoutParams.WRAP_CONTENT);
                params.gravity = Gravity.BOTTOM;
                final int dp18 = (int) TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, 18, fragment.getResources().getDisplayMetrics());
                params.topMargin = dp18 * 7;
                params.rightMargin = dp18;
                params.bottomMargin = dp18 * 3 ;
                mFloatBtnWrapper.setLayoutParams(params);
                content.addView(mFloatBtnWrapper);
                //mFloatBtnWrapper.setOnTouchListener(new StackViewTouchListener(mFloatBtnWrapper, dp18 / 4));
                mFloatBtnWrapper.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        GameLog.log("===========================fragment 是否为空  "+fragment);
                        if(ZHBetManager.getSingleton().onListSize()<3){
                            fragment.showMessage("下注单数最少3注");
                            return;
                        }
                        BetOrderZHSubmitDialog.newInstance(form,userMoney,active,ZHBetManager.getSingleton().onShowViewListData()).show(context.getSupportFragmentManager());
                    }
                });
            }
        }
        //ZHBetManager.getSingleton().onClearData();
    }

    public void onShowNumber(String text){
        floatNumber.setText(text);
    }

    public void onCloseView(){
        if(null!=content&&null!=mFloatBtnWrapper){
            content.removeView(mFloatBtnWrapper);
            mFloatBtnWrapper = null;
            GameLog.log("============================mFloatBtnWrapper=null==========================="+mFloatBtnWrapper);
        }
        ZHBetManager.getSingleton().onClearData();
    }

}
