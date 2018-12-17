package com.hgapp.a6668.common.util;

import com.hgapp.a6668.base.IMessageView;
import com.hgapp.common.util.NetworkUtils;

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
