package me.yokeyword.sample.demo_wechat;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.util.Log;

import com.example.commonliberary.R;

import me.yokeyword.fragmentation.SupportActivity;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.fragmentation.anim.DefaultHorizontalAnimator;
import me.yokeyword.fragmentation.anim.FragmentAnimator;
import me.yokeyword.fragmentation.helper.FragmentLifecycleCallbacks;
import me.yokeyword.sample.demo_wechat.ui.fragment.MainFragment;

/**
 * 仿微信交互方式Demo   tip: 多使用右上角的"查看栈视图"
 * Created by YoKeyword on 16/6/30.
 */
public class MainActivity extends SupportActivity {

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.wechat_activity_main);

        if (savedInstanceState == null) {
            loadRootFragment(R.id.fl_container, MainFragment.newInstance());
        }

        // 可以监听该Activity下的所有Fragment的18个 生命周期方法
        registerFragmentLifecycleCallbacks(new FragmentLifecycleCallbacks() {

            @Override
            public void onFragmentSupportVisible(SupportFragment fragment) {
                Log.i("WebviewActivity", "onFragmentSupportVisible--->" + fragment.getClass().getSimpleName());
            }

            @Override
            public void onFragmentCreated(SupportFragment fragment, Bundle savedInstanceState) {
                super.onFragmentCreated(fragment, savedInstanceState);
            }
            // 省略其余生命周期方法
        });
    }

    @Override
    public void onBackPressedSupport() {
        // 对于 4个类别的主Fragment内的回退back逻辑,已经在其onBackPressedSupport里各自处理了
        super.onBackPressedSupport();
    }

    @Override
    public FragmentAnimator onCreateFragmentAnimator() {
        // 设置横向(和安卓4.x动画相同)
        return new DefaultHorizontalAnimator();
    }
}
