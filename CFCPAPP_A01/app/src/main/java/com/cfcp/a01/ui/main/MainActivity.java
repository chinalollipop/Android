package com.cfcp.a01.ui.main;

import android.os.Bundle;
import android.support.annotation.Nullable;

import com.cfcp.a01.CFConstant;
import com.cfcp.a01.R;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.ToastUtils;

import me.yokeyword.fragmentation.SupportActivity;
import me.yokeyword.fragmentation.anim.DefaultNoAnimator;
import me.yokeyword.fragmentation.anim.FragmentAnimator;

import static com.cfcp.a01.common.utils.Utils.getContext;

public class MainActivity extends SupportActivity {
    // 再点一次退出程序时间设置
    private static final long WAIT_TIME = 2000L;
    private long TOUCH_TIME = 0;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);
        if (savedInstanceState == null) {
            loadRootFragment(R.id.fl_container, MainFragment.newInstance());
        }
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
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
//                ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_TOKEN, "");
                ACache.get(getApplicationContext()).put(CFConstant.USERNAME_LOAD_LUNCHER,"0");
                finish();
            } else {
                TOUCH_TIME = System.currentTimeMillis();
                ToastUtils.showLongToast(getString(R.string.n_exit_app));
            }
        }
    }

    @Override
    public FragmentAnimator onCreateFragmentAnimator() {
        // 设置动画 主Activity无动画效果 所有的动画在PNBaseFragment去设置总体动画，如果每个Fragment有特殊要求的动画，重载此方法即可。
        return new DefaultNoAnimator();
    }

}
