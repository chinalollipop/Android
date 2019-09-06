package com.gmcp.gm.common.http;

import java.util.concurrent.atomic.AtomicLong;

/**
 * Created by Nereus on 2017/5/10.
 * 线程工程，PN独有
 */

public  class PNThreadFactory {

    private PNThreadFactory(){}

    @Override
    public Object clone()
    {
        throw new NullPointerException("cloning " + getClass().getSimpleName() + " is not allowed");
    }
    private static AtomicLong atomicLong = new AtomicLong(1);

    /**
     * 创建PN独有线程，一目了然，知道线程来自PN
     * @param runnable
     * @return
     */
    public static Thread createThread(Runnable runnable)
    {
        return new Thread(runnable,"PNThread:" + atomicLong.getAndIncrement());
    }
}
