package com.hgapp.a6668;

import android.os.Bundle;
import android.support.annotation.Nullable;

import com.hgapp.a6668.common.useraction.UserActionHandler;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.common.util.GameLog;
import com.hgapp.common.util.ToastUtils;

import me.yokeyword.fragmentation.SupportActivity;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.fragmentation.anim.DefaultNoAnimator;
import me.yokeyword.fragmentation.anim.FragmentAnimator;
import me.yokeyword.fragmentation.helper.FragmentLifecycleCallbacks;

public class MainActivity extends SupportActivity {

    // 再点一次退出程序时间设置
    private static final long WAIT_TIME = 2000L;
    private long TOUCH_TIME = 0;
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
    }

    @Override
    public void onDestroy()
    {
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

}
