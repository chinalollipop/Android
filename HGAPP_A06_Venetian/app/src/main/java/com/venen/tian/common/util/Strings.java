package com.venen.tian.common.util;

import java.util.Arrays;
import java.util.Collection;
import java.util.Date;

/**
 * Created by Nereus on 2017/6/15.
 */
public final class Strings {

    private Strings(){}

    public static String toString(Collection collection)
    {
        if(collection == null)
        {
            return "null";
        }
        StringBuilder stringBuilder = new StringBuilder();
        stringBuilder.append("----print collections-----" + collection.getClass().getName()+"-----------");
        stringBuilder.append("\n");
        int count = 0;
        for(Object obj : collection)
        {
            count++;
            stringBuilder.append(count + obj.toString());
            stringBuilder.append("\n");
        }

        return stringBuilder.toString();
    }

    public static String toDateString(long timestamp)
    {
        if(0==timestamp)
        {
            return "unknown";
        }
        Date date = new Date(timestamp);
        return date.toString();
    }

    public static String toString(String[][] array)
    {
        if(null == array || array.length == 0)
        {
            return "";
        }

        StringBuilder builder = new StringBuilder();
        for(int i = 0;i < array.length;i++)
        {
            builder.append(Arrays.toString(array[i]));
            builder.append("\n");
        }
        return builder.toString();
    }

    public static String[] toArray(String value,String divider) {
        if(null == value || value.length() == 0 )
        {
            return new String[0];
        }
        String[] property = value.split(divider);
        return property;
    }

    public static String toString(String[] array,String divider) {
        if(null == array || array.length == 0)
        {
            return "";
        }
        StringBuilder builder = new StringBuilder();
        for(String property : array)
        {
            builder.append(property);
            builder.append(divider);
        }
        builder.deleteCharAt(builder.length()-1);
        return builder.toString();
    }
}
