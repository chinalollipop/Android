package com.hgapp.bet365.common.useraction;

import android.app.Activity;
import android.support.v4.app.Fragment;

/**
 * Created by Nereus on 2017/5/26.
 */

public interface IUserAction {
    public void onAppStart();
    public void onAppStop();
    public void onActivityStart(Activity activity);
    public void onActivityStop(Activity activity);
    public void onFragmentStart(Fragment fragment);
    public void onFragmentStop(Fragment fragment);
}
