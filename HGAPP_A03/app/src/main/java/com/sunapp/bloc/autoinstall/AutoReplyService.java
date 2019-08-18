package com.sunapp.bloc.autoinstall;
import android.accessibilityservice.AccessibilityService;
import android.annotation.TargetApi;
import android.app.KeyguardManager;
import android.content.Context;
import android.os.Build;
import android.os.PowerManager;
import android.util.Log;
import android.view.accessibility.AccessibilityEvent;
import android.view.accessibility.AccessibilityNodeInfo;
import android.widget.Toast;

import java.util.List;

/**
 * Created by zjhl on 2017/10/28.
 */
public class AutoReplyService extends AccessibilityService {

    private boolean canGet = false;//能否点击红包
    private boolean enableKeyguard = true;//默认有屏幕锁

    //窗口状态
    private static final int WINDOW_NONE = 0;
    private static final int WINDOW_LAUNCHER = 3;
    //当前窗口
    private int mCurrentWindow = WINDOW_NONE;

    //锁屏、解锁相关
    private KeyguardManager km;
    private KeyguardManager.KeyguardLock kl;
    //唤醒屏幕相关
    private PowerManager pm;
    private PowerManager.WakeLock wl = null;

    private boolean isInstall;


    //唤醒屏幕和解锁
    private void wakeAndUnlock(boolean unLock) {
        if (unLock) {
            //若为黑屏状态则唤醒屏幕
            if (!pm.isScreenOn()) {
                //获取电源管理器对象，ACQUIRE_CAUSES_WAKEUP这个参数能从黑屏唤醒屏幕
                wl = pm.newWakeLock(PowerManager.SCREEN_BRIGHT_WAKE_LOCK | PowerManager.ACQUIRE_CAUSES_WAKEUP, "bright");
                //点亮屏幕
                wl.acquire();
            }
            //若在锁屏界面则解锁直接跳过锁屏
            if (km.inKeyguardRestrictedInputMode()) {
                //设置解锁标志，以判断抢完红包能否锁屏
                enableKeyguard = false;
                //解锁
                kl.disableKeyguard();
            }
        } else {
            //如果之前解过锁则加锁以恢复原样
            if (!enableKeyguard) {
                //锁屏
                kl.reenableKeyguard();
            }
            //若之前唤醒过屏幕则释放之使屏幕不保持常亮
            if (wl != null) {
                wl.release();
                wl = null;
            }
        }
    }

    @TargetApi(Build.VERSION_CODES.JELLY_BEAN)
    @Override
    public void onAccessibilityEvent(AccessibilityEvent event) {
        int eventType = event.getEventType();
        System.out.println("onAccessibilityEvent..............................."+eventType);
        switch (eventType) {
            //第一步：监听通知栏消息
            case AccessibilityEvent.TYPE_NOTIFICATION_STATE_CHANGED:
                System.out.println("onAccessibilityEvent.......................................TYPE_NOTIFICATION_STATE_CHANGED.");
                break;
            //第二步：监听是否进入apk安装界面
            case AccessibilityEvent.TYPE_WINDOW_STATE_CHANGED:
                System.out.println("TYPE_WINDOW_STATE_CHANGED...............................");
                AccessibilityNodeInfo rootNode = getRootInActiveWindow();
                if (rootNode != null) {
                    int count = rootNode.getChildCount();
                    for (int i = 0; i < count; i++) {
                        final AccessibilityNodeInfo nodeInfo = rootNode.getChild(i);
                        if (nodeInfo == null) {
                            continue;
                        }
                        System.out.println("nodeInfo.getClassName.............................." + nodeInfo.getClassName());
                        new Thread(new Runnable() {
                            @Override
                            public void run() {
                                clickNode(nodeInfo);
                            }
                        }).start();
                    }
                }
                break;

            case AccessibilityEvent.TYPE_WINDOW_CONTENT_CHANGED:
                System.out.println("onAccessibilityEvent.......................................TYPE_WINDOW_CONTENT_CHANGED");
                break;
        }

    }

    private void clickNode(AccessibilityNodeInfo nodeInfo) {
        //“下一步”和“安装”都是button
        if ("android.widget.Button".equals(nodeInfo.getClassName()) && !isInstall) {
            isInstall = true;
            System.out.println("TYPE_WINDOW_STATE_CHANGED...............................1");
            AccessibilityNodeInfo nodeInfo1 = getRootInActiveWindow();
            System.out.println("TYPE_WINDOW_STATE_CHANGED...............................2");
            if (nodeInfo1 != null) {
                System.out.println("TYPE_WINDOW_STATE_CHANGED...............................3");
                //找到"下一步"
                List<AccessibilityNodeInfo> nodeInfos = nodeInfo1.findAccessibilityNodeInfosByText("下一步");
                System.out.println("下一步.................................");
                if (nodeInfos.size() == 0) {
                    nodeInfos = nodeInfo1.findAccessibilityNodeInfosByText("安装");
                    System.out.println("安装.................................");
                    if (nodeInfos.size() == 0) {
                        System.out.println("完成.................................");
                        nodeInfos = nodeInfo1.findAccessibilityNodeInfosByText("完成");
                    }
                }
                System.out.println("TYPE_WINDOW_STATE_CHANGED...............................4..." + nodeInfos.size());
                for (int j = 0; j < nodeInfos.size(); j++) {
                    System.out.println("recycle.................................." + j);
                    AccessibilityNodeInfo accessibilityNodeInfo = nodeInfos.get(j);
                    System.out.println("TYPE_WINDOW_STATE_CHANGED...............................5..." + accessibilityNodeInfo.getClassName());
                    //找到图片最外层的位置
//                    if (!isInstall) {
                    if ("android.widget.Button".equals(accessibilityNodeInfo.getClassName())) {
                        accessibilityNodeInfo.performAction(AccessibilityNodeInfo.ACTION_CLICK);
                        System.out.println("TYPE_WINDOW_STATE_CHANGED...............................clicked...");
                        try {
                            Thread.sleep(1000);
                        } catch (InterruptedException e) {
                            e.printStackTrace();
                        }
                        isInstall = false;
                        clickNode(nodeInfo);
                        break;
                    }
                }
            }
        }
    }

    @Override
    public void onInterrupt() {
        System.out.println("onInterrupt.............................");

    }

    @Override
    protected void onServiceConnected() {
        super.onServiceConnected();
        Log.i("demo", "开启");
        System.out.println("onServiceConnected.......................................");
        //获取电源管理器对象
        pm = (PowerManager) getSystemService(Context.POWER_SERVICE);
        //得到键盘锁管理器对象
        km = (KeyguardManager) getSystemService(Context.KEYGUARD_SERVICE);
        //初始化一个键盘锁管理器对象
        kl = km.newKeyguardLock("unLock");
        Toast.makeText(this, "_已开启应用自动安装服务_", Toast.LENGTH_LONG).show();
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        System.out.println("onDestroy.......................................");
        Log.i("demo", "关闭");
        wakeAndUnlock(false);
        Toast.makeText(this, "_已关闭应用自动安装服务_", Toast.LENGTH_LONG).show();
    }

}
