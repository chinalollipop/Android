package com.vene.tian;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.content.LocalBroadcastManager;

import com.vene.tian.common.useraction.UserActionHandler;
import com.vene.tian.common.util.ACache;
import com.vene.tian.common.util.HGConstant;
import com.vene.tian.homepage.push.ExampleUtil;
import com.vene.common.util.GameLog;
import com.vene.common.util.ToastUtils;

import me.yokeyword.fragmentation.SupportActivity;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.fragmentation.anim.DefaultNoAnimator;
import me.yokeyword.fragmentation.anim.FragmentAnimator;
import me.yokeyword.fragmentation.helper.FragmentLifecycleCallbacks;

public class MainActivity extends SupportActivity {

    // 再点一次退出程序时间设置
    private static final long WAIT_TIME = 2000L;
    private long TOUCH_TIME = 0;
    public static boolean isForeground = false;
    private MessageReceiver mMessageReceiver;
    public static final String MESSAGE_RECEIVED_ACTION = "com.example.jpushdemo.MESSAGE_RECEIVED_ACTION";
    public static final String KEY_TITLE = "title";
    public static final String KEY_MESSAGE = "message";
    public static final String KEY_EXTRAS = "extras";
    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        if (savedInstanceState == null) {
            loadRootFragment(R.id.fl_container, MainFragment.newInstance());
        }

        // 可以监听该Activity下的所有Fragment的18个 生命周期方法
        registerFragmentLifecycleCallbacks(new FragmentLifecycleCallbacks() {

            @Override
            public void onFragmentSupportVisible(SupportFragment fragment) {
                GameLog.log("onFragmentSupportVisible--->" + fragment.getClass().getSimpleName());
                if(null != fragment)
                {
                    fragment.onVisible();
                }
            }

            @Override
            public void onFragmentCreated(SupportFragment fragment, Bundle savedInstanceState) {
                super.onFragmentCreated(fragment, savedInstanceState);
            }
            // 省略其余生命周期方法
        });
        UserActionHandler.getInstance().onActivityStart(this);
        registerMessageReceiver();
    }

    public void registerMessageReceiver() {
        mMessageReceiver = new MessageReceiver();
        IntentFilter filter = new IntentFilter();
        filter.setPriority(IntentFilter.SYSTEM_HIGH_PRIORITY);
        filter.addAction(MESSAGE_RECEIVED_ACTION);
        LocalBroadcastManager.getInstance(this).registerReceiver(mMessageReceiver, filter);
    }


    @Override
    public void onDestroy()
    {
        LocalBroadcastManager.getInstance(this).unregisterReceiver(mMessageReceiver);
        super.onDestroy();
        UserActionHandler.getInstance().onActivityStop(this);
    }


    @Override
    public void onBackPressedSupport() {
        // 对于 4个类别的主Fragment内的回退back逻辑,已经在其onBackPressedSupport里各自处理了
        //super.onBackPressedSupport();
        //------------------add by AK 2017-09-15 添加需求双击返回键退出程序---------------------
        if (this.getSupportFragmentManager().getBackStackEntryCount() > 1) {
            pop();
        }else{
            if (System.currentTimeMillis() - TOUCH_TIME < WAIT_TIME) {
                ACache.get(getApplicationContext()).put(HGConstant.USERNAME_LOGIN_STATUS+ACache.get(getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT), "0");
                //ACache.get(getApplicationContext()).put(HGConstant.USERNAME_LOGIN_ACCOUNT, "");
                ACache.get(getApplicationContext()).put(HGConstant.APP_CP_COOKIE,"");
                ACache.get(getApplicationContext()).put(HGConstant.USERNAME_ALIAS, "");
                ACache.get(getApplicationContext()).put(HGConstant.USERNAME_LOGOUT, "true");
                /*String isLogoChange = ACache.get(getApplicationContext()).getAsString("change_logo");
                if(Check.isEmpty(isLogoChange)){
                    GameLog.log("当前的状态是 "+isLogoChange);
                    ACache.get(getApplicationContext()).put("change_logo","1");
                    EntranceUtils.getInstance().enable(this,"com.sunapp.bloc.EntranceSpec");
                }*/
                finish();
            } else {
                TOUCH_TIME = System.currentTimeMillis();
                ToastUtils.showLongToast(getString(R.string.n_exit_app));
            }
        }
    }

    @Override
    protected FragmentAnimator onCreateFragmentAnimator() {
        // 设置动画 主Ativity无动画效果 所有的动画在PNBaseFragment去设置总体动画，如果每个Fragment有特殊要求的动画，重载此方法即可。
        return new DefaultNoAnimator();
    }

    @Override
    protected void onResume() {
        isForeground = true;
        super.onResume();
    }


    @Override
    protected void onPause() {
        isForeground = false;
        super.onPause();
    }

    public class MessageReceiver extends BroadcastReceiver {

        @Override
        public void onReceive(Context context, Intent intent) {
            try {
                if (MESSAGE_RECEIVED_ACTION.equals(intent.getAction())) {
                    String messge = intent.getStringExtra(KEY_MESSAGE);
                    String extras = intent.getStringExtra(KEY_EXTRAS);
                    StringBuilder showMsg = new StringBuilder();
                    showMsg.append(KEY_MESSAGE + " : " + messge + "\n");
                    if (!ExampleUtil.isEmpty(extras)) {
                        showMsg.append(KEY_EXTRAS + " : " + extras + "\n");
                    }
                    setCostomMsg(showMsg.toString());
                }
            } catch (Exception e){
            }
        }

    }

    private void setCostomMsg(String msg){
        /*if (null != msgText) {
            msgText.setText(msg);
            msgText.setVisibility(android.view.View.VISIBLE);
        }*/
    }

}
