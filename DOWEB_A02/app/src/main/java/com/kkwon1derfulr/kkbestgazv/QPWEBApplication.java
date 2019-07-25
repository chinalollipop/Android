package com.kkwon1derfulr.kkbestgazv;

import android.app.Application;
import android.util.Log;


import com.kkwon1derfulr.kkbestgazv.utils.Check;
import com.kkwon1derfulr.kkbestgazv.utils.CommentUtils;
import com.kkwon1derfulr.kkbestgazv.utils.FileIOUtils;
import com.kkwon1derfulr.kkbestgazv.utils.FileUtils;
import com.kongzue.dialog.util.DialogSettings;
import com.tencent.smtt.sdk.QbSdk;
import com.zhy.http.okhttp.OkHttpUtils;

import java.io.File;

import cn.jpush.android.api.JPushInterface;

public class QPWEBApplication extends Application {
    private static QPWEBApplication qpwebApplication;
    private String comment;
    @Override
    public void onCreate() {
        super.onCreate();
        qpwebApplication = this;
        initconfigCommentClient();
        OkHttpUtils.getInstance()
                .init(this)
                .debug(true, "okHttp")
                .timeout(20 * 1000);

        JPushInterface.setDebugMode(true); 	// 设置开启日志,发布时请关闭日志
        JPushInterface.init(this);     		// 初始化 JPush
        //搜集本地tbs内核信息并上报服务器，服务器返回结果决定使用哪个内核。
        QbSdk.PreInitCallback cb = new QbSdk.PreInitCallback() {

            @Override
            public void onViewInitFinished(boolean arg0) {
                // TODO Auto-generated method stub
                //x5內核初始化完成的回调，为true表示x5内核加载成功，否则表示x5内核加载失败，会自动切换到系统内核。
                Log.d("app", " onViewInitFinished is " + arg0);
            }

            @Override
            public void onCoreInitFinished() {
                // TODO Auto-generated method stub
            }
        };
        //x5内核初始化接口
        QbSdk.initX5Environment(getApplicationContext(),  cb);
    }

    public void  initconfigCommentClient(){

        //设置dialog主题
        DialogSettings.isUseBlur = true;//设置是否启用模糊
        DialogSettings.theme = DialogSettings.THEME.LIGHT;
        DialogSettings.tipTheme = DialogSettings.THEME.LIGHT;
        DialogSettings.cancelable = true;

        String filePath = FileUtils.getFilePath(getApplicationContext(),"")+"/markets.txt";
        //先读本地文件，没有的话，再读comments，然后在保存到本地
        comment = FileIOUtils.readFile2String(filePath);
        if(Check.isEmpty(comment)){
            comment =  CommentUtils.readAPK(new File(getApplicationContext().getPackageCodePath()));
            FileIOUtils.writeFileFromString(filePath,comment);
        }else{
            FileIOUtils.writeFileFromString(filePath,comment);
        }
    }

    public static QPWEBApplication instance(){
        return qpwebApplication;
    }

    public String getCommentData(){
        return comment;
    }

}