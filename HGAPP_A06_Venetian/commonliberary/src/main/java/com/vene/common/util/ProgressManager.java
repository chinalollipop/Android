package com.vene.common.util;

import android.view.MotionEvent;
import android.view.View;
import android.view.animation.AlphaAnimation;
import android.view.animation.Animation;
import android.widget.ProgressBar;

/**
 * Created by Nereus on 2017/4/12.
 */

public class ProgressManager {
    private ProgressBar progressBar;
    private int lastprogress = 0;
    private View progressgroup;
    private boolean switchShowProgress = true;
    public ProgressManager(ProgressBar progressBar,View progressgroup)
    {
        this.progressBar = progressBar;
        this.progressgroup = progressgroup;
    }

    public void setShowProgressOnce(boolean flag)
    {
        switchShowProgress = flag;
    }
    private void onMaxProgressArrived()
    {
        switchShowProgress = false;
    }
    public void hideProgress()
    {
        progressBar.setVisibility(View.GONE);
        progressgroup.setVisibility(View.GONE);
    }

    public void setPrgress(int value)
    {
        if(!switchShowProgress)
        {
            hideProgress();
            return;
        }
        if(value > 0 && value < 100)
        {
            progressBar.setVisibility(View.VISIBLE);
            progressgroup.setVisibility(View.VISIBLE);
            if(value <= 80 && value - lastprogress >=5)
            {
                progressBar.setProgress(value);
                lastprogress = value;
            }
            else
            {
                progressBar.setProgress(value);
            }


        }
        else
        {
            if(progressgroup.getVisibility() == View.VISIBLE)
            {
                progressBar.setVisibility(View.GONE);
                progressgroup.setVisibility(View.GONE);
                progressgroup.setOnTouchListener(new View.OnTouchListener() {
                    @Override
                    public boolean onTouch(View view, MotionEvent motionEvent) {
                        return false;
                    }
                });
                Animation animation = new AlphaAnimation(1.0f,0.0f);
                animation.setDuration(500);
                progressBar.startAnimation(animation);

                onMaxProgressArrived();
            }

        }
    }
}
