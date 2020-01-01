package com.nhg.common.util;

import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.content.pm.PermissionInfo;
import android.os.Binder;

/**
 * Created by Nereus on 2017/5/3.
 */

public class ServiceProguard {

    /**
     * 检查出没有权限，会抛出异常 在{@linkplain android.app.Service#onBind(Intent)}中检查
     * @param context
     * @param permission
     */
    public void enforceCallingPermission(Context context, String permission)
    {
        context.enforceCallingPermission(permission, "TODO: message if thrown");
    }

    /**
     * 检查IPC通信使用者是否有权限访问  在{@linkplain android.app.Service#onBind(Intent)}中检查
     * @param context
     * @param permission
     * @return true如有有权限，否则返回false
     */
    public boolean checkCallingPermision(Context context, String permission)
    {
        return PackageManager.PERMISSION_GRANTED == context.checkPermission(permission,Binder.getCallingPid(),Binder.getCallingUid());
    }

    /**
     * 检查IPC使用者的包名
     * 在{@linkplain android.app.Service#onBind(Intent)}中检查
     * @param context
     */
    private void checkIPCCallingPackage(Context context)
    {
        PackageManager packageManager = context.getPackageManager();
        String[] packages = packageManager.getPackagesForUid(Binder.getCallingUid());
        if(null != packages)
        {
            for(String pkg : packages)
            {
                if(null != pkg)
                {
                    try {
                        PackageInfo packageInfo = packageManager.getPackageInfo(pkg,PackageManager.GET_PERMISSIONS);
                        PermissionInfo[] permissionInfos = packageInfo.permissions;
                        if(null != permissionInfos)
                        {
                            for(PermissionInfo permissionInfo : permissionInfos)
                            {
                                //TODO 在这里检查permission
                            }
                        }
                    } catch (PackageManager.NameNotFoundException e) {
                        e.printStackTrace();
                    }
                }
            }
        }
    }
}
