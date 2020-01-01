package com.nhg.xhg.common;

//import com.ylgj.app.common.services.AppDownloadServiceBinder;

/**
 * Created by Nereus on 2017/4/27.
 * 内存管理，例如释放可释放的内存，防止app被系统kill
 */

public class MemoryManager {
    private static MemoryManager sManager;
    public static MemoryManager getManager()
    {
        if(null == sManager)
        {
            synchronized (MemoryManager.class)
            {
                if(null == sManager)
                {
                    sManager = new MemoryManager();
                }
            }
        }

        return sManager;
    }

    private MemoryManager(){}
    @Override
    public Object clone()
    {
        throw  new RuntimeException("sorry,you can not copy memory manager");
    }

    /**
     * 当内存紧张的时候释放可释放的一切内存：图片、缓存、可终止的任务
     */
    public void releaseMemory()
    {
        //AppDownloadServiceBinder.getBinder().forceStop();
    }
}
