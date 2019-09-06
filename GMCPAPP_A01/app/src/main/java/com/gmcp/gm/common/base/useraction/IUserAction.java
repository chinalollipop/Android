package com.gmcp.gm.common.base.useraction;

import android.app.Activity;
import android.support.v4.app.Fragment;

/**
 * Created by Daniel on 2017/5/26.
 */

public interface IUserAction {
    void onAppStart();
    void onAppStop();
    void onActivityStart(Activity activity);
    void onActivityStop(Activity activity);
    void onFragmentStart(Fragment fragment);
    void onFragmentStop(Fragment fragment);
}
