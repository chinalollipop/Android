package com.cfcp.a01.common.http;

import android.content.Context;
import android.support.annotation.StringRes;

import com.cfcp.a01.common.utils.Utils;

/**
 * Created by daniel on 2018/5/30.
 */

public class ResHelper {

    private ResHelper(){}

    public static String getString(@StringRes int stringid)
    {
        Context context = Utils.getContext();
        return context.getString(stringid);
    }

    /**
     *
     * @param mipmapname
     * @return 0如果没有找到
     */
    public static int getMipmap(String mipmapname)
    {
        Context context = Utils.getContext();
        int id = context.getResources().getIdentifier(mipmapname,"mipmap",context.getPackageName());
        return id;
    }
}
