package com.sunapp.common;

import android.app.Application;
import android.support.annotation.CallSuper;

import com.lzy.okgo.OkGo;
import com.sunapp.common.util.Utils;

import me.yokeyword.sample.App;

/**
 * Created by Nereus on 2017/4/25.
 */

public class CommonApplication extends Application {

    @CallSuper
    public void onCreate() {
        super.onCreate();
        App.doOnCreate();
        Utils.init(getApplicationContext());

        OkGo.init(this);
        OkGo.getInstance().debug("okgo").setRetryCount(1);
    }

    @CallSuper
    public void onTrimMemory(int level) {
        super.onTrimMemory(level);
        if(level >= Application.TRIM_MEMORY_MODERATE)
        {
            onTrimMemoryNow();
        }
    }

    /**
     * 内存预警，应在此立即释放可释放之内存
     */
    protected void onTrimMemoryNow()
    {

    }
    @CallSuper
    public void onTerminate() {
        super.onTerminate();
    }
}
