package com.nhg.xhg.common.util;

import com.nhg.common.util.NetworkUtils;
import com.nhg.xhg.base.IMessageView;

/**
 * Created by Nereus on 2017/4/26.
 */

public class Presenters {

    private Presenters(){}

    public static  boolean breakByNetworkError(IMessageView messageView)
    {
        return breakByNetworkError(messageView,null);
    }

    public static  boolean breakByNetworkError(IMessageView messageView,String msg)
    {
        if(null == msg)
        {
            msg = new String("当前没有网络");
        }
        if(!NetworkUtils.isConnected())
        {
            messageView.showMessage(msg);
            return true;
        }

        return false;
    }
}
