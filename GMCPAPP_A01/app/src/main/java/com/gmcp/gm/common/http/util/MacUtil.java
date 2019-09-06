package com.gmcp.gm.common.http.util;


import com.gmcp.gm.common.http.request.AppTextMessageRequest;

/**
 * Created by Daniel on 2018/8/24.
 */

public class MacUtil {

    public static String generateMac(AppTextMessageRequest messageRequest)
    {
        StringBuilder builder = new StringBuilder();
        builder.append(messageRequest.getAppRefer());
        //builder.append(messageRequest.getChannelID());
        builder.append(messageRequest.getDigiSign());
        builder.append(messageRequest.getEncryptType());
        builder.append(messageRequest.getPid());
        builder.append(messageRequest.getTimestamp());
        builder.append(messageRequest.getLocale());
        builder.append(messageRequest.getDeviceId());
        if(null != messageRequest.getData())
        {
            builder.append((String)messageRequest.getData());
        }
        String tokenmd5 = Md5Utils.getMd5(messageRequest.getToken());
        builder.append(tokenmd5);
        String mac = Md5Utils.getMd5(builder.toString());
        //Timber.d("mac:%s",mac);
        return mac;
    }
}
