package com.hgapp.betnhg.common.util;

import android.app.AlarmManager;
import android.app.PendingIntent;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageManager;

import java.util.HashMap;
import java.util.Map;

public class EntranceUtils {
    private static final String TAG = "EntranceUtils";
    private Map<String, ComponentName> mEntranceMap;
    private PackageManager mPackageManager;

    public static EntranceUtils getInstance() {
        return InstanceHolder.instance;
    }

    /**
     * 初始化
     * @param context           context
     * @param componentNames    组件别名数组，需要将默认入口别名放在第一位
     */
    public void init(Context context, String... componentNames) {
        if (mPackageManager == null) {
            mPackageManager = context.getPackageManager();
        }
        if (mEntranceMap == null) {
            mEntranceMap = new HashMap<>();
        }
        for (int i = 0; i < componentNames.length; i++) {
            ComponentName value = new ComponentName(context, componentNames[i]);
            mEntranceMap.put(componentNames[i], value);
            //默认情况下，组件的 enable 状态为 default，需要手动设置
            mPackageManager.setComponentEnabledSetting(
                    value,
                    i == 0 ? PackageManager.COMPONENT_ENABLED_STATE_ENABLED : PackageManager.COMPONENT_ENABLED_STATE_DISABLED,
                    PackageManager.DONT_KILL_APP);
        }
    }

    public void enable(Context context, String componentName) {
        ComponentName component = mEntranceMap.get(componentName);
        if (component != null && mPackageManager.getComponentEnabledSetting(component) == PackageManager.COMPONENT_ENABLED_STATE_DISABLED) {
            for (Map.Entry<String, ComponentName> entry : mEntranceMap.entrySet()) {
                int newState = componentName.equals(entry.getKey()) ? PackageManager.COMPONENT_ENABLED_STATE_ENABLED : PackageManager.COMPONENT_ENABLED_STATE_DISABLED;
                mPackageManager.setComponentEnabledSetting(
                        entry.getValue(),
                        newState,
                        PackageManager.DONT_KILL_APP);
            }
            //restartApp(context);
        }
    }

    private void restartApp(Context context) {
        Intent intent = context.getPackageManager()
                .getLaunchIntentForPackage(context.getPackageName());
        PendingIntent restartIntent = PendingIntent.getActivity(context, 0, intent, PendingIntent.FLAG_ONE_SHOT);
        AlarmManager mgr = (AlarmManager) context.getSystemService(Context.ALARM_SERVICE);
        mgr.set(AlarmManager.RTC, System.currentTimeMillis() + 2000, restartIntent); // 2秒钟后重启应用
        System.exit(0);

    }

    private static class InstanceHolder {
        private static EntranceUtils instance = new EntranceUtils();
    }

}
