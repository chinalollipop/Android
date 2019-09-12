package com.sands.corp.upgrade.downunit;

import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Environment;
import android.util.Log;
import android.widget.Toast;

import java.io.BufferedInputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;

/**
 * Created by Windows 10 on 2017/12/17.
 */

public class DownLoadManager {
    public static File getFileFromServer(Context mContext,String path)throws Exception{
        if(Environment.getExternalStorageState().equals(Environment.MEDIA_MOUNTED)){

            Log.i("TAG","sdcard mounted");
            URL url = new URL(path);
            HttpURLConnection conn = (HttpURLConnection)url.openConnection();
            conn.setConnectTimeout(5000);
            conn.setRequestMethod("GET");
            conn.setDoInput(true);
            //pd.setMax(conn.getContentLength());
            InputStream is = conn.getInputStream();
            String savePath = mContext.getFilesDir().toString()+File.separator;
            File file = new File(savePath,"yb_update.apk");
            file.setReadable(true,false);
            file.setWritable(true);
            try {
                Runtime.getRuntime().exec("chmod 705 "+savePath);
                Runtime.getRuntime().exec("chmod 604 "+file.toString());
            } catch (IOException e) {
                e.printStackTrace();
            }
            if( file.exists()){
            }else{
                file.createNewFile();
            }
            FileOutputStream fos = new FileOutputStream(file);
            BufferedInputStream bis = new BufferedInputStream(is);
            byte[] buffer = new byte[1024];
            int len;
            int total = 0;
            while( (len = bis.read(buffer))!= -1){
                Log.i("TAG","read size :"+len);
                fos.write(buffer,0,len);
                total+=len;
                //pd.setProgress(total);
            }
            //pd.dismiss();
            if( total != conn.getContentLength()){
                Toast.makeText(mContext, "下载终中断", Toast.LENGTH_SHORT).show();
            }
            fos.close();
            bis.close();
            is.close();
            return file;
        }else{
            return null;
        }
    }

    public  void installApk(Context mContext,File file) {
        Intent intent = new Intent();
        intent.setAction(Intent.ACTION_VIEW);
        intent.setDataAndType(Uri.parse("file://" + file.toString()), "application/vnd.android.package-archive");
        mContext.startActivity(intent);
    }
}
