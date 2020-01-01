package com.nhg.common.upgrade;

import android.content.Context;
import android.content.pm.PackageManager;

import com.nhg.common.util.CopyUtil;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;

/**
 * Created by Nereus on 2017/4/17.
 */

public class UpgradeModel {

    public boolean isUpgradeAvailable(Context context,String url,UpgradeInfo outputgradeInfo)
    {
        /*Request request = new Request.Builder().url(url).build();
        try {
            Response response = Client.getClient().newCall(request).execute();
            if(response.isSuccessful())
            {
                String body = response.body().string();
                GameLog.log("is upgrade available :" + body);
                Gson gson = new Gson();
                UpgradeInfo upgradeInfo = gson.fromJson(body,UpgradeInfo.class);
                if(null == upgradeInfo || null == upgradeInfo.version)
                {
                    GameLog.loge("no upgrade version in remote");
                    return false;
                }
                outputgradeInfo = upgradeInfo;
                String localversionname = getLocalVersionName(context);
                GameLog.log("version local:" + localversionname + ",remote:" + upgradeInfo.version + ",compare:" + upgradeInfo.version.compareTo(localversionname));
                if(shouldUpgrade(localversionname,upgradeInfo.version))
                {
                    GameLog.log("it is time to upgrade");
                    return true;
                }

            }
        } catch (IOException e) {
            e.printStackTrace();
        } catch (PackageManager.NameNotFoundException e) {
            e.printStackTrace();
        }*/
        return false;
    }

    public boolean shouldUpgrade(String localversion,String remoteversion)
    {
        return remoteversion.compareTo(localversion) > 0;
    }

    private String getLocalVersionName(Context context)throws PackageManager.NameNotFoundException
    {
        String localversionname = context.getPackageManager().getPackageInfo(context.getPackageName(),0).versionName;
        return localversionname;
    }

    public  File getFile(Context context)
    {
        File file = new File(context.getCacheDir(),"upgrade89563.apk");
        return file;
    }
    public File download(String url,Context context) {
       /* File destFile = getFile(context);
        if(destFile.exists())
        {
            destFile.delete();
        }
        Request request = new Request.Builder().url(url).get().build();
        try {
            Response response = Client.getClient().newCall(request).execute();
            if(response.isSuccessful())
            {
                copy(response.body().byteStream(),destFile);
                GameLog.log("dowload file " + destFile.getName() + " size:" + destFile.length()/1024.0 + "kb");
                return destFile;
            }
        } catch (IOException e) {
            e.printStackTrace();
        }*/

        return null;
    }

    private void copy(InputStream input,File destFile)throws IOException
    {
        OutputStream output = new FileOutputStream(destFile);
        CopyUtil.copy(input, output);
        input.close();
        output.close();
    }
}
