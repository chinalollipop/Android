package com.hgapp.app.common.util;

import android.os.Handler;

import com.hgapp.common.util.Timber;

/**
 * Created by Nereus on 2017/8/14.
 * tick tick tick
 */

public class Ticker {

    public interface OnTickerListener
    {
        public void onTick(int total,int left);
        public void onEnd();
    }
    private boolean stop = true;
    private int total;
    private int tick;
    private Handler uiHandler = new Handler();
    private OnTickerListener listener;
    public Ticker(){

    }

    public void setOnTickerListener(OnTickerListener listener)
    {
        this.listener=listener;
    }

    public void setTotalTicker(int total)
    {
        this.total = total;
    }

    public void begin()
    {
        if(stop)
        {
            stop=false;
            tick = total;
            uiHandler.post(run);
        }

    }

    public void stop()
    {
        stop = true;
        uiHandler.removeCallbacks(run);
    }

    private Runnable run = new Runnable() {
        @Override
        public void run() {
            if(stop)
            {
                Timber.d("ticker is stopped");
                return;
            }
            tick --;
            Timber.d("tick:%d",tick);
            if(tick >0)
            {
                if(null != listener)
                {
                    listener.onTick(total,tick);
                }
                uiHandler.postDelayed(run,1000);
            }
            else
            {
                if(null != listener)
                {
                    listener.onEnd();
                }
                uiHandler.removeCallbacks(this);
                stop = true;
            }
        }
    };
}
