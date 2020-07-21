package com.hgapp.bet365.common.useraction;

import android.app.Activity;
import android.support.v4.app.Fragment;

import java.util.HashSet;

/**
 * Created by Nereus on 2017/5/26.
 */
public class UserActionHandler implements IUserAction {
    private static UserActionHandler ourInstance = new UserActionHandler();
    private HashSet<IUserAction> actionHandlers = new HashSet<>();
    public static UserActionHandler getInstance() {
        return ourInstance;
    }

    public void setUserActionHandler(IUserAction handler)
    {
        actionHandlers.add(handler);
    }
    private UserActionHandler() {
    }

    @Override
    public void onAppStart() {
        for(IUserAction handler : actionHandlers)
        {
            handler.onAppStart();
        }
    }

    @Override
    public void onAppStop() {
        for(IUserAction handler : actionHandlers)
        {
            handler.onAppStop();
        }
    }

    @Override
    public void onActivityStart(Activity activity) {
        for(IUserAction handler : actionHandlers)
        {
            handler.onActivityStart(activity);
        }
    }

    @Override
    public void onActivityStop(Activity activity) {
        for(IUserAction handler : actionHandlers)
        {
            handler.onActivityStop(activity);
        }
    }

    @Override
    public void onFragmentStart(Fragment fragment) {
        for(IUserAction handler : actionHandlers)
        {
            handler.onFragmentStart(fragment);
        }
    }

    @Override
    public void onFragmentStop(Fragment fragment) {
        for(IUserAction handler : actionHandlers)
        {
            handler.onFragmentStop(fragment);
        }
    }
}
