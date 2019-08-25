package me.yokeyword.sample.demo_wechat.base;

import android.Manifest;
import android.app.Activity;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.provider.Settings;
import android.support.annotation.NonNull;
import android.support.v4.app.ActivityCompat;
import android.support.v4.content.ContextCompat;
import android.support.v7.app.AlertDialog;

import com.hg3366.common.util.Check;
import com.hg3366.common.util.GameLog;

/**
 * Created by Nereus on 2017/5/4.
 */

public class BasePermissionMainFragment extends BaseMainFragment {

    public static final int PERMISSION_REQUEST_CODE = 1222;
    public void doOnRequestPermission()
    {
        int permission = ContextCompat.checkSelfPermission(getActivity().getApplicationContext(), permission());
        if(permission == PackageManager.PERMISSION_GRANTED)
        {
            doLocate();
        }
        else
        {
            boolean should = ActivityCompat.shouldShowRequestPermissionRationale(getActivity(),permission());
            GameLog.log("shouldShowRequestPermissionRationale:" + should);
            if(!should)
            {
                shouldShowRequestPermissionRationale();
            }
            else
            {
                requestPermissions(new String[]{permission()},PERMISSION_REQUEST_CODE);
            }

        }
    }

    private void doLocate()
    {

    }


    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions,
                                           @NonNull int[] grantResults) {
        if(PERMISSION_REQUEST_CODE == requestCode)
        {
            if(!Check.isEmpty(permissions) && null !=grantResults && grantResults.length > 0)
            {
                if(permission().equals(permissions[0]))
                {
                    //用户同意授权
                    if(PackageManager.PERMISSION_GRANTED == grantResults[0])
                    {
                        GameLog.log("用户同意访问位置");
                    }
                    //用户拒绝授权
                    else
                    {
                        GameLog.log("用户拒绝访问位置");
                    }
                }
            }
        }
    }


    /**
     * 子类覆盖
     * @return
     */
    protected String permission()
    {
        return Manifest.permission.ACCESS_FINE_LOCATION;
    }
    /**
     * 子类覆盖
     * @return
     */
    protected void shouldShowRequestPermissionRationale() {

        if(null == _mActivity)
        {
            return;
        }
        new AlertDialog.Builder(_mActivity)
                .setTitle("我要访问位置，给我权限吧")
                .setPositiveButton("好吧", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        if(null == _mActivity)
                        {
                            return;
                        }
                        startInstalledAppDetailsActivity(_mActivity);
                        dialog.dismiss();
                    }
                })
                .setNegativeButton("不给", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        dialog.dismiss();
                    }
                })
                .show();
    }

    private void startInstalledAppDetailsActivity(final Activity context) {
        if (context == null) {
            return;
        }
        final Intent i = new Intent();
        i.setAction(Settings.ACTION_APPLICATION_DETAILS_SETTINGS);
        i.addCategory(Intent.CATEGORY_DEFAULT);
        i.setData(Uri.parse("package:" + context.getPackageName()));
        i.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        i.addFlags(Intent.FLAG_ACTIVITY_NO_HISTORY);
        i.addFlags(Intent.FLAG_ACTIVITY_EXCLUDE_FROM_RECENTS);
        context.startActivity(i);
    }
}
