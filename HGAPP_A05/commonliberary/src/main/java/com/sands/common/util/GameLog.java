package com.sands.common.util;

/**
 * Created by Nereus on 2017/4/17.
 * log类，推荐使用{@linkplain Timber}
 */

public class GameLog {
    public static boolean PRINT_LOG=false;
    private GameLog(){}

    public static void log(String msg)
    {
        if(PRINT_LOG)
        {
           Timber.i(msg);
        }

    }

    public static void loge(Throwable throwable)
    {
        if(PRINT_LOG)
        {
            Timber.e(throwable);
        }
    }
    public static void loge(String msg)
    {
        if(PRINT_LOG)
        {
            Timber.i(msg);
        }
    }

}
