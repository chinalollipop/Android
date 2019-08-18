package com.sunapp.bloc.common.util;


import com.sunapp.common.util.Check;
import com.sunapp.common.util.Timber;

import java.math.BigDecimal;

/**
 * Created by Daniel on 2018/7/31.
 */

public class GameShipHelper {

    private GameShipHelper(){}

    /**
     * 格式化2位小数点
     * @param number
     * @return
     */
    public static String formatNumber(String number){
        if(Check.isEmpty(number)){
            return "";
        }
        //方法一
        Double dMoney = Double.parseDouble(number);
        number=String.format("%.2f",dMoney);
        return number;
        //方法二
        /*NumberFormat nf = NumberFormat.getNumberInstance();
        nf.setMaximumFractionDigits(2);
        System.out.println(nf.format(d));
        */
    }

    /**
     * 格式化金额。e.g.
     * 1000.50 --> 1,000.50
     * @param money
     * @return
     */
    public static String formatMoney(String money)
    {
        try
        {
            if(null==money){
                return "";
            }
            Double dMoney = Double.parseDouble(money);
            money=String.format("%.2f",dMoney);
        }
        catch (NumberFormatException e)
        {
            Timber.e("无法将%s转换成Double",money);
        }

        StringBuilder builder=new StringBuilder(money);
        money = builder.reverse().toString();
        builder=new StringBuilder();
        int length = money.length();
        int start = money.indexOf(".");
        if(-1 == start)
        {
            start = 0;
        }
        else
        {
            start++;
            builder.append(money.substring(0,start));
        }

        int end = 0;
        while((end = start + 3) < length )
        {
            builder.append(money.substring(start,end));
            builder.append(",");
            start = end;
        }
        builder.append(money.substring(start));
        String result = builder.reverse().toString();
        return result;
    }


    public static String formatMoney2(String money)
    {
        try
        {
            if(null==money){
                return "";
            }
            Double dMoney = Double.parseDouble(money);
            money=String.format("%.2f",dMoney);
        }
        catch (NumberFormatException e)
        {
            Timber.e("无法将%s转换成Double",money);
        }

        StringBuilder builder=new StringBuilder(money);
        money = builder.reverse().toString();
        builder=new StringBuilder();
        int length = money.length();
        int start = money.indexOf(".");
        if(-1 == start)
        {
            start = 0;
        }
        else
        {
            start++;
            builder.append(money.substring(0,start));
        }

        /*int end = 0;
        while((end = start + 3) < length )
        {
            builder.append(money.substring(start,end));
            builder.append(",");
            start = end;
        }*/
        builder.append(money.substring(start));
        String result = builder.reverse().toString();
        return result;
    }

    public static String getIntegerString(String text)
    {
        String result = text;
        int index = text.indexOf(".");
        if(-1!=index)
        {
            result = text.substring(0,index);
        }
        return result;
    }
    public static String selectMax(String localCash,String localGame)
    {
        BigDecimal bdLocalCash = new BigDecimal(GameShipHelper.getIntegerString(localCash));
        BigDecimal bgLocalGame = new BigDecimal(GameShipHelper.getIntegerString(localGame));
        BigDecimal total = new BigDecimal("0");
        total = total.add(bdLocalCash);
        total = total.add(bgLocalGame);
        Timber.d("cash:%s+game:%s=%s",bdLocalCash.toString(),bgLocalGame.toString(),total.toString());
        return total.toPlainString();
    }
}
