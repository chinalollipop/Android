package com.qpweb.a01.base;

import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.qpweb.a01.base.event.StartBrotherEvent;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import me.yokeyword.fragmentation.SupportFragment;

/**
 * 懒加载
 * Created by YoKeyword on 16/6/5.
 */
public abstract class BaseMainFragment extends SupportFragment {
    // 再点一次退出程序时间设置
/*    private static final long WAIT_TIME = 2000L;
    private long TOUCH_TIME = 0;*/

    /**
     * 处理回退事件
     * modify by ak  此方法暂时注释了  基类acitiviy有相关的处理逻辑
     * @return
     */
    /*@Override
    public boolean onBackPressedSupport() {
        if (System.currentTimeMillis() - TOUCH_TIME < WAIT_TIME) {
            _mActivity.finish();
        } else {
            TOUCH_TIME = System.currentTimeMillis();
            Toast.makeText(_mActivity, R.string.press_again_exit, Toast.LENGTH_SHORT).show();
        }
        return false;
    }*/

    /**
     * 退出本fragment返回上一个fragment
     */
    public void finish()
    {
        _mActivity.onBackPressed();
    }

}
