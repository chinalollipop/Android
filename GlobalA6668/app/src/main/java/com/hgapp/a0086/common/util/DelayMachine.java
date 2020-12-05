package com.hgapp.a0086.common.util;

import android.os.Handler;

import com.hgapp.common.util.Timber;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by Nereus on 2017/8/23.
 */

public class DelayMachine {

    private Handler handler = new Handler();
    private List<Runnable> list = new ArrayList<>();
    public DelayMachine(){}

    public void delay(Runnable runnable,long timeMillis)
    {
        Timber.d("delay %d",timeMillis);
        list.add(runnable);
        handler.postDelayed(runnable,timeMillis);
    }

    public void cancel()
    {
        Timber.d("cancel");
        for(Runnable runnable:list)
        {
            handler.removeCallbacks(runnable);
        }
    }
}
