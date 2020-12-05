package com.hgapp.a0086.common.util;

import android.content.Context;
import android.content.SharedPreferences;
import android.preference.PreferenceManager;

import com.hgapp.common.util.Utils;

public class Store {

    public static void setLanguageLocal(Context context, String language){
        SharedPreferences preferences;
        SharedPreferences.Editor editor;
        preferences = PreferenceManager.getDefaultSharedPreferences(context);
        editor = preferences.edit();
        editor.putString("language", language);
        editor.commit();
    }

    public static String getLanguageLocal(){
        SharedPreferences preferences;
        preferences = PreferenceManager.getDefaultSharedPreferences(Utils.getContext());
        String language = preferences.getString("language", "");
        return language;
    }
}
