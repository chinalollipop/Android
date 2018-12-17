package com.hgapp.a6668.upgrade.downunit;

import android.os.Handler;
import android.os.Looper;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by Daniel on 2018/8/16.
 */

public class ThreadDispatcher {
    private Handler uiHandler;
    private List<Runnable> list;
    public ThreadDispatcher()
    {
        list = new ArrayList<>();
        uiHandler = new Handler(Looper.getMainLooper());
    }
    public void runOnUi(Runnable runnable)
    {
        list.add(runnable);
        uiHandler.postDelayed(runnable,0);
    }

    public void exit()
    {
        for(Runnable callback : list)
        {
            uiHandler.removeCallbacks(callback);
        }

    }
}
