package com.youjie.cfcpnew.receiver;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;

import com.youjie.cfcpnew.R;
import com.youjie.cfcpnew.rxbus.EventMsg;
import com.youjie.cfcpnew.rxbus.RxBus;
import com.youjie.cfcpnew.utils.AppToast;

public class NetBroadCastReceiver extends BroadcastReceiver {
    private boolean networkConnect = false;

    @Override
    public void onReceive(Context context, Intent intent) {

        //如果是在开启wifi连接和有网络状态下
        if (ConnectivityManager.CONNECTIVITY_ACTION.equals(intent.getAction())) {
            ConnectivityManager cm = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
            NetworkInfo info = intent.getParcelableExtra(ConnectivityManager.EXTRA_NETWORK_INFO);

            if (NetworkInfo.State.CONNECTED == info.getState()) {
                //连接状态 处理自己的业务逻辑
                if (networkConnect) {
                    AppToast.showLongText(context, R.string.networktips);
                    EventMsg eventMsg = new EventMsg();
                    eventMsg.setMsg("重连");
                    RxBus.getInstance().post(eventMsg);
                }
                networkConnect = false;
            } else {
                networkConnect = true;
                AppToast.showLongText(context, R.string.nonetworktips);
            }
        }
    }
}
