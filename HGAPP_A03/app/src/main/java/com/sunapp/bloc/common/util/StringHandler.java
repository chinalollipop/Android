package com.sunapp.bloc.common.util;

/**
 * Created by Nereus on 2017/7/24.
 */

public class StringHandler {

    private String astring;
    public StringHandler(String astring)
    {
        this.astring = astring;
    }

    public StringHandler filterEnterChar()
    {
        StringBuilder builder = new StringBuilder();
        for(char achar : astring.toCharArray())
        {
            int intchar = (int)achar;
            if(10 != intchar)
            {
                builder.append(achar);
            }
        }
        astring = builder.toString();
        return this;
    }
    public StringHandler filter(String specialchar)
    {
        this.astring = astring.replace(specialchar,"");
        return this;
    }

    public StringHandler trimZeroStart()
    {
        String text = astring;
        if(!text.isEmpty() && text.startsWith("0")) {
            text = text.substring(1);
            astring = text;
        }
        return this;
    }
    /**
     * 保留小数点后{@code smallNumberAfterDot}位数,e.g.  80.5600
     * @param smallNumberAfterDot
     * @return
     */
    public StringHandler keepSmallNumber(int smallNumberAfterDot)
    {
        int dotIndex = astring.indexOf(".");
        if(-1!= dotIndex && dotIndex < astring.length()-2)
        {
            final int size = astring.length();
            String end = astring.substring(dotIndex+1,size);
            final int endsize = end.length();
            if(endsize - smallNumberAfterDot > 0)
            {
               astring = astring.substring(0,size-(endsize-smallNumberAfterDot));
            }
        }
        return this;
    }

    public StringHandler keepMaxLength(int maxLength)
    {
        int index = astring.indexOf(".");
        int length = 0;
        if(-1 == index)
        {
            length = astring.length();
        }
        else
        {
            length = index;
        }
        if(length > maxLength)
        {
            String newString = astring.substring(0,maxLength);
            astring = newString;
        }
        return this;
    }
    public String getString()
    {
        return astring;
    }

    @Override
    public String toString() {
        return "StringHandler{" +
                "astring='" + astring + '\'' +
                '}';
    }
}
