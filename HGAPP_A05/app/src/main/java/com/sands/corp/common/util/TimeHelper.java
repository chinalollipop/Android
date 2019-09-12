package com.sands.corp.common.util;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;


/**
 * <pre>
 *     author: Daniel
 *     blog  : http://blankj.com
 *     time  : 2019/1/1
 *     desc  : 时间相关工具类
 * </pre>
 */
public class TimeHelper {

    private TimeHelper() {
        throw new UnsupportedOperationException("TimeHelper class / u can't instantiate me...");
    }

    /**
     * <p>在工具类中经常使用到工具类的格式化描述，这个主要是一个日期的操作类，所以日志格式主要使用 SimpleDateFormat的定义格式.</p>
     * 格式的意义如下： 日期和时间模式 <br>
     * <p>日期和时间格式由日期和时间模式字符串指定。在日期和时间模式字符串中，未加引号的字母 'A' 到 'Z' 和 'a' 到 'z'
     * 被解释为模式字母，用来表示日期或时间字符串元素。文本可以使用单引号 (') 引起来，以免进行解释。"''"
     * 表示单引号。所有其他字符均不解释；只是在格式化时将它们简单复制到输出字符串，或者在分析时与输入字符串进行匹配。
     * </p>
     * 定义了以下模式字母（所有其他字符 'A' 到 'Z' 和 'a' 到 'z' 都被保留）： <br>
     * <table border="1" cellspacing="1" cellpadding="1" summary="Chart shows pattern letters, date/time component,
     * presentation, and examples.">
     * <tr>
     * <th align="left">字母</th>
     * <th align="left">日期或时间元素</th>
     * <th align="left">表示</th>
     * <th align="left">示例</th>
     * </tr>
     * <tr>
     * <td><code>G</code></td>
     * <td>Era 标志符</td>
     * <td>Text</td>
     * <td><code>AD</code></td>
     * </tr>
     * <tr>
     * <td><code>y</code> </td>
     * <td>年 </td>
     * <td>Year </td>
     * <td><code>1996</code>; <code>96</code> </td>
     * </tr>
     * <tr>
     * <td><code>M</code> </td>
     * <td>年中的月份 </td>
     * <td>Month </td>
     * <td><code>July</code>; <code>Jul</code>; <code>07</code> </td>
     * </tr>
     * <tr>
     * <td><code>w</code> </td>
     * <td>年中的周数 </td>
     * <td>Number </td>
     * <td><code>27</code> </td>
     * </tr>
     * <tr>
     * <td><code>W</code> </td>
     * <td>月份中的周数 </td>
     * <td>Number </td>
     * <td><code>2</code> </td>
     * </tr>
     * <tr>
     * <td><code>D</code> </td>
     * <td>年中的天数 </td>
     * <td>Number </td>
     * <td><code>189</code> </td>
     * </tr>
     * <tr>
     * <td><code>d</code> </td>
     * <td>月份中的天数 </td>
     * <td>Number </td>
     * <td><code>10</code> </td>
     * </tr>
     * <tr>
     * <td><code>F</code> </td>
     * <td>月份中的星期 </td>
     * <td>Number </td>
     * <td><code>2</code> </td>
     * </tr>
     * <tr>
     * <td><code>E</code> </td>
     * <td>星期中的天数 </td>
     * <td>Text </td>
     * <td><code>Tuesday</code>; <code>Tue</code> </td>
     * </tr>
     * <tr>
     * <td><code>a</code> </td>
     * <td>Am/pm 标记 </td>
     * <td>Text </td>
     * <td><code>PM</code> </td>
     * </tr>
     * <tr>
     * <td><code>H</code> </td>
     * <td>一天中的小时数（0-23） </td>
     * <td>Number </td>
     * <td><code>0</code> </td>
     * </tr>
     * <tr>
     * <td><code>k</code> </td>
     * <td>一天中的小时数（1-24） </td>
     * <td>Number </td>
     * <td><code>24</code> </td>
     * </tr>
     * <tr>
     * <td><code>K</code> </td>
     * <td>am/pm 中的小时数（0-11） </td>
     * <td>Number </td>
     * <td><code>0</code> </td>
     * </tr>
     * <tr>
     * <td><code>h</code> </td>
     * <td>am/pm 中的小时数（1-12） </td>
     * <td>Number </td>
     * <td><code>12</code> </td>
     * </tr>
     * <tr>
     * <td><code>m</code> </td>
     * <td>小时中的分钟数 </td>
     * <td>Number </td>
     * <td><code>30</code> </td>
     * </tr>
     * <tr>
     * <td><code>s</code> </td>
     * <td>分钟中的秒数 </td>
     * <td>Number </td>
     * <td><code>55</code> </td>
     * </tr>
     * <tr>
     * <td><code>S</code> </td>
     * <td>毫秒数 </td>
     * <td>Number </td>
     * <td><code>978</code> </td>
     * </tr>
     * <tr>
     * <td><code>z</code> </td>
     * <td>时区 </td>
     * <td>General time zone </td>
     * <td><code>Pacific Standard Time</code>; <code>PST</code>; <code>GMT-08:00</code> </td>
     * </tr>
     * <tr>
     * <td><code>Z</code> </td>
     * <td>时区 </td>
     * <td>RFC 822 time zone </td>
     * <td><code>-0800</code> </td>
     * </tr>
     * </table>
     * <pre>
     *                          HH:mm    15:44
     *                         h:mm a    3:44 下午
     *                        HH:mm z    15:44 CST
     *                        HH:mm Z    15:44 +0800
     *                     HH:mm zzzz    15:44 中国标准时间
     *                       HH:mm:ss    15:44:40
     *                     yyyy-MM-dd    2016-08-12
     *               yyyy-MM-dd HH:mm    2016-08-12 15:44
     *            yyyy-MM-dd HH:mm:ss    2016-08-12 15:44:40
     *       yyyy-MM-dd HH:mm:ss zzzz    2016-08-12 15:44:40 中国标准时间
     *  EEEE yyyy-MM-dd HH:mm:ss zzzz    星期五 2016-08-12 15:44:40 中国标准时间
     *       yyyy-MM-dd HH:mm:ss.SSSZ    2016-08-12 15:44:40.461+0800
     *     yyyy-MM-dd'T'HH:mm:ss.SSSZ    2016-08-12T15:44:40.461+0800
     *   yyyy.MM.dd G 'at' HH:mm:ss z    2016.08.12 公元 at 15:44:40 CST
     *                         K:mm a    3:44 下午
     *               EEE, MMM d, ''yy    星期五, 八月 12, '16
     *          hh 'o''clock' a, zzzz    03 o'clock 下午, 中国标准时间
     *   yyyyy.MMMMM.dd GGG hh:mm aaa    02016.八月.12 公元 03:44 下午
     *     EEE, d MMM yyyy HH:mm:ss Z    星期五, 12 八月 2016 15:44:40 +0800
     *                  yyMMddHHmmssZ    160812154440+0800
     *     yyyy-MM-dd'T'HH:mm:ss.SSSZ    2016-08-12T15:44:40.461+0800
     * EEEE 'DATE('yyyy-MM-dd')' 'TIME('HH:mm:ss')' zzzz    星期五 DATE(2016-08-12) TIME(15:44:40) 中国标准时间
     * </pre>
     */
    public static final SimpleDateFormat DEFAULT_SDF = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault());


    /**
     * 将时间戳转为时间字符串
     * <p>格式为yyyy-MM-dd HH:mm:ss</p>
     *
     * @param milliseconds 毫秒时间戳
     * @return 时间字符串
     */
    public static String milliseconds2String(long milliseconds) {
        return milliseconds2String(milliseconds, DEFAULT_SDF);
    }

    /**
     * 将时间戳转为时间字符串
     * <p>格式为用户自定义</p>
     *
     * @param milliseconds 毫秒时间戳
     * @param format       时间格式
     * @return 时间字符串
     */
    public static String milliseconds2String(long milliseconds, SimpleDateFormat format) {
        return format.format(new Date(milliseconds));
    }

    /**
     * 将时间字符串转为时间戳
     * <p>格式为yyyy-MM-dd HH:mm:ss</p>
     *
     * @param time 时间字符串
     * @return 毫秒时间戳
     */
    public static long string2Milliseconds(String time) {
        return string2Milliseconds(time, DEFAULT_SDF);
    }

    /**
     * 将时间字符串转为时间戳
     * <p>格式为用户自定义</p>
     *
     * @param time   时间字符串
     * @param format 时间格式
     * @return 毫秒时间戳
     */
    public static long string2Milliseconds(String time, SimpleDateFormat format) {
        try {
            return format.parse(time).getTime();
        } catch (ParseException e) {
            e.printStackTrace();
        }
        return -1;
    }

    /**
     * 将时间字符串转为Date类型
     * <p>格式为yyyy-MM-dd HH:mm:ss</p>
     *
     * @param time 时间字符串
     * @return Date类型
     */
    public static Date string2Date(String time) {
        return string2Date(time, DEFAULT_SDF);
    }

    /**
     * 将时间字符串转为Date类型
     * <p>格式为用户自定义</p>
     *
     * @param time   时间字符串
     * @param format 时间格式
     * @return Date类型
     */
    public static Date string2Date(String time, SimpleDateFormat format) {
        return new Date(string2Milliseconds(time, format));
    }

    /**
     * 将Date类型转为时间字符串
     * <p>格式为yyyy-MM-dd HH:mm:ss</p>
     *
     * @param time Date类型时间
     * @return 时间字符串
     */
    public static String date2String(Date time) {
        return date2String(time, DEFAULT_SDF);
    }

    /**
     * 将Date类型转为时间字符串
     * <p>格式为用户自定义</p>
     *
     * @param time   Date类型时间
     * @param format 时间格式
     * @return 时间字符串
     */
    public static String date2String(Date time, SimpleDateFormat format) {
        return format.format(time);
    }

    /**
     * 将Date类型转为时间戳
     *
     * @param time Date类型时间
     * @return 毫秒时间戳
     */
    public static long date2Milliseconds(Date time) {
        return time.getTime();
    }

    /**
     * 将时间戳转为Date类型
     *
     * @param milliseconds 毫秒时间戳
     * @return Date类型时间
     */
    public static Date milliseconds2Date(long milliseconds) {
        return new Date(milliseconds);
    }

//    /**
//     * 毫秒时间戳单位转换（单位：unit）
//     *
//     * @param milliseconds 毫秒时间戳
//     * @param unit         <ul>
//     *                     <li>{@link TimeUnit#MSEC}: 毫秒</li>
//     *                     <li>{@link TimeUnit#SEC }: 秒</li>
//     *                     <li>{@link TimeUnit#MIN }: 分</li>
//     *                     <li>{@link TimeUnit#HOUR}: 小时</li>
//     *                     <li>{@link TimeUnit#DAY }: 天</li>
//     *                     </ul>
//     * @return unit时间戳
//     */
//    private static long milliseconds2Unit(long milliseconds, TimeUnit unit) {
//        switch (unit) {
//            case MSEC:
//                return milliseconds / MSEC;
//            case SEC:
//                return milliseconds / SEC;
//            case MIN:
//                return milliseconds / MIN;
//            case HOUR:
//                return milliseconds / HOUR;
//            case DAY:
//                return milliseconds / DAY;
//        }
//        return -1;
//    }
//
//    /**
//     * 获取两个时间差（单位：unit）
//     * <p>time1和time2格式都为yyyy-MM-dd HH:mm:ss</p>
//     *
//     * @param time0 时间字符串1
//     * @param time1 时间字符串2
//     * @param unit  <ul>
//     *              <li>{@link TimeUnit#MSEC}: 毫秒</li>
//     *              <li>{@link TimeUnit#SEC }: 秒</li>
//     *              <li>{@link TimeUnit#MIN }: 分</li>
//     *              <li>{@link TimeUnit#HOUR}: 小时</li>
//     *              <li>{@link TimeUnit#DAY }: 天</li>
//     *              </ul>
//     * @return unit时间戳
//     */
//    public static long getIntervalTime(String time0, String time1, TimeUnit unit) {
//        return getIntervalTime(time0, time1, unit, DEFAULT_SDF);
//    }
//
//    /**
//     * 获取两个时间差（单位：unit）
//     * <p>time1和time2格式都为format</p>
//     *
//     * @param time0  时间字符串1
//     * @param time1  时间字符串2
//     * @param unit   <ul>
//     *               <li>{@link TimeUnit#MSEC}: 毫秒</li>
//     *               <li>{@link TimeUnit#SEC }: 秒</li>
//     *               <li>{@link TimeUnit#MIN }: 分</li>
//     *               <li>{@link TimeUnit#HOUR}: 小时</li>
//     *               <li>{@link TimeUnit#DAY }: 天</li>
//     *               </ul>
//     * @param format 时间格式
//     * @return unit时间戳
//     */
//    public static long getIntervalTime(String time0, String time1, TimeUnit unit, SimpleDateFormat format) {
//        return Math.abs(milliseconds2Unit(string2Milliseconds(time0, format)
//                - string2Milliseconds(time1, format), unit));
//    }
//
//    /**
//     * 获取两个时间差（单位：unit）
//     * <p>time1和time2都为Date类型</p>
//     *
//     * @param time0 Date类型时间1
//     * @param time1 Date类型时间2
//     * @param unit  <ul>
//     *              <li>{@link TimeUnit#MSEC}: 毫秒</li>
//     *              <li>{@link TimeUnit#SEC }: 秒</li>
//     *              <li>{@link TimeUnit#MIN }: 分</li>
//     *              <li>{@link TimeUnit#HOUR}: 小时</li>
//     *              <li>{@link TimeUnit#DAY }: 天</li>
//     *              </ul>
//     * @return unit时间戳
//     */
//    public static long getIntervalTime(Date time0, Date time1, TimeUnit unit) {
//        return Math.abs(milliseconds2Unit(date2Milliseconds(time1)
//                - date2Milliseconds(time0), unit));
//    }
//
//    /**
//     * 获取当前时间
//     *
//     * @return 毫秒时间戳
//     */
//    public static long getCurTimeMills() {
//        return System.currentTimeMillis();
//    }
//
//    /**
//     * 获取当前时间
//     * <p>格式为yyyy-MM-dd HH:mm:ss</p>
//     *
//     * @return 时间字符串
//     */
//    public static String getCurTimeString() {
//        return date2String(new Date());
//    }
//
//    /**
//     * 获取当前时间
//     * <p>格式为用户自定义</p>
//     *
//     * @param format 时间格式
//     * @return 时间字符串
//     */
//    public static String getCurTimeString(SimpleDateFormat format) {
//        return date2String(new Date(), format);
//    }
//
//    /**
//     * 获取当前时间
//     * <p>Date类型</p>
//     *
//     * @return Date类型时间
//     */
//    public static Date getCurTimeDate() {
//        return new Date();
//    }
//
//    /**
//     * 获取与当前时间的差（单位：unit）
//     * <p>time格式为yyyy-MM-dd HH:mm:ss</p>
//     *
//     * @param time 时间字符串
//     * @param unit <ul>
//     *             <li>{@link TimeUnit#MSEC}:毫秒</li>
//     *             <li>{@link TimeUnit#SEC }:秒</li>
//     *             <li>{@link TimeUnit#MIN }:分</li>
//     *             <li>{@link TimeUnit#HOUR}:小时</li>
//     *             <li>{@link TimeUnit#DAY }:天</li>
//     *             </ul>
//     * @return unit时间戳
//     */
//    public static long getIntervalByNow(String time, TimeUnit unit) {
//        return getIntervalByNow(time, unit, DEFAULT_SDF);
//    }
//
//    /**
//     * 获取与当前时间的差（单位：unit）
//     * <p>time格式为format</p>
//     *
//     * @param time   时间字符串
//     * @param unit   <ul>
//     *               <li>{@link TimeUnit#MSEC}: 毫秒</li>
//     *               <li>{@link TimeUnit#SEC }: 秒</li>
//     *               <li>{@link TimeUnit#MIN }: 分</li>
//     *               <li>{@link TimeUnit#HOUR}: 小时</li>
//     *               <li>{@link TimeUnit#DAY }: 天</li>
//     *               </ul>
//     * @param format 时间格式
//     * @return unit时间戳
//     */
//    public static long getIntervalByNow(String time, TimeUnit unit, SimpleDateFormat format) {
//        return getIntervalTime(getCurTimeString(), time, unit, format);
//    }
//
//    /**
//     * 获取与当前时间的差（单位：unit）
//     * <p>time为Date类型</p>
//     *
//     * @param time Date类型时间
//     * @param unit <ul>
//     *             <li>{@link TimeUnit#MSEC}: 毫秒</li>
//     *             <li>{@link TimeUnit#SEC }: 秒</li>
//     *             <li>{@link TimeUnit#MIN }: 分</li>
//     *             <li>{@link TimeUnit#HOUR}: 小时</li>
//     *             <li>{@link TimeUnit#DAY }: 天</li>
//     *             </ul>
//     * @return unit时间戳
//     */
//    public static long getIntervalByNow(Date time, TimeUnit unit) {
//        return getIntervalTime(getCurTimeDate(), time, unit);
//    }

    /**
     * 判断闰年
     *
     * @param year 年份
     * @return {@code true}: 闰年<br>{@code false}: 平年
     */
    public static boolean isLeapYear(int year) {
        return year % 4 == 0 && year % 100 != 0 || year % 400 == 0;
    }
    public static String convertToTime(long time) {
        SimpleDateFormat format = new SimpleDateFormat("MM-dd HH:mm:ss");
        Date date = new Date(time);
        return format.format(date);
    }
    public static  String convertToDetailTime(long time) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        Date date = new Date(time);
        return format.format(date);
    }

    /*
     * 毫秒转化时分秒毫秒
     */
    public static String formatTime(Long ms) {
        Integer ss = 1000;
        Integer mi = ss * 60;
        Integer hh = mi * 60;
        Integer dd = hh * 24;

        Long day = ms / dd;
        Long hour = (ms - day * dd) / hh;
        Long minute = (ms - day * dd - hour * hh) / mi;
        Long second = (ms - day * dd - hour * hh - minute * mi) / ss;
        Long milliSecond = ms - day * dd - hour * hh - minute * mi - second * ss;

        StringBuffer sb = new StringBuffer();
        if(day > 0) {
            sb.append(day+"天");
        }
        if(hour > 0) {
            sb.append(hour+"小时");
        }
        if(minute > 0) {
            sb.append(minute+"分");
        }
        if(second > 0) {
            sb.append(second+"秒");
        }
        if(milliSecond > 0) {
            sb.append(milliSecond+"毫秒");
        }
        return sb.toString();
    }



    /**
     * 将现在的正常字符串格式时间转换成距离1970的数字时间
     * 比如字符串格式时间："2017-12-15 21:49:03"
     * 转换后的数字时间："1513345743"
     * String dateStr="1970-1-1 08:00:00";
     *         SimpleDateFormat sdf=new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
     *         long aftertime=0;
     *         try {
     *             Object d1=sdf.parse(time).getTime();
     *             Date miDate = sdf.parse(dateStr);
     *             Object t1=miDate.getTime();
     *             long d1time=Long.parseLong(d1.toString())/1000;
     *             long t1time=Long.parseLong(t1.toString())/1000;
     *             aftertime = d1time-t1time;
     *         } catch (ParseException e) {
     *             e.printStackTrace();
     *         }
     * @param time
     * @return
     */
    public static Long timeToSecond(String time, String dateStr) {
        SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        long aftertime = 0;
        try {
            /*Object d1=sdf.parse(time).getTime();
            Date miDate = sdf.parse(dateStr);
            Object t1=miDate.getTime();
            long d1time=Long.parseLong(d1.toString().substring(0,10));
            long t1time=Long.parseLong(t1.toString().substring(0,10));*/
            long d1 = sdf.parse(time).getTime();
            Date miDate = sdf.parse(dateStr);
            long t1 = miDate.getTime();
            long d1time = d1 / 1000;
            long t1time = t1 / 1000;
            aftertime = d1time - t1time;
        } catch (ParseException e) {
            e.printStackTrace();
        }
        /*String dateStr="1970-1-1 08:00:00";
        SimpleDateFormat sdf=new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        long aftertime=0;
        try {
            Object d1=sdf.parse(time).getTime();
            Date miDate = sdf.parse(dateStr);
            Object t1=miDate.getTime();
            long d1time=Long.parseLong(d1.toString())/1000;
            long t1time=Long.parseLong(t1.toString())/1000;
            aftertime = d1time-t1time;
        } catch (ParseException e) {
            e.printStackTrace();
        }*/

        return aftertime;

    }

    public static long compareToNowDate(Date date){
        Date nowdate=new Date();
        //format date pattern
        SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        //convert to millions seconds
        long time=StringToDate(formatter.format(nowdate));
        long serverTime=StringToDate(formatter.format(date));
        //convert to seconds
        long minTime=Math.abs(serverTime-time)/1000;
        return minTime;
    }

    private static long DateToLong(Date time){
        SimpleDateFormat st=new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");//yyyyMMddHHmmss
        return Long.parseLong(st.format(time.getTime()));
    }

    private static long StringToDate(String s){
        long time=0;
        SimpleDateFormat sd=new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        try {
            time=sd.parse(s).getTime();
        } catch (java.text.ParseException e) {
            System.out.println("输入的日期格式有误！");
            e.printStackTrace();
        }
        return time;
    }




    /**
     *
     * @param second
     * @return 时间戳转成成剩余的时间
     */
    public static String getTimeString(long second) {
        //GameLog.log("时间戳的长度 "+second);
        //int days = (int) (second / (60 * 60 * 24));
        /*int hours = (int) ((second % (60 * 60 * 24)) / (60 * 60));
        int min = (int) (second % (60 * 60)) / 60;
        int sec = (int) (second % 60);*/

        int days=((int)second)/(3600*24);
        int hours=((int)second)%(3600*24)/3600;
        int min = ((int)second)%(3600*24)%3600/60;
        int sec = ((int)second)%(3600*24)%3600%60%60;

        StringBuffer sb = new StringBuffer();
            if(days==1){
                hours +=24;
            }else if(days==2){
                hours +=48;
            }else  if(days==3){
                hours +=72;
            }
//        sb.append(hours).append("时").append(min).append("分").append(sec).append("秒");
        //sb.append(hours>9?hours:"0"+hours).append(":").append(min>9?min:"0"+min).append(":").append(sec>9?sec:"0"+sec);//.append(" : ")
        sb.append(hours>9?hours+":":hours>0?"0"+hours+":":"").append(min>9?min:"0"+min).append(":").append(sec>9?sec:"0"+sec);//.append(" : ")
		/*if (days > 0) {
			sb.append(days).append("天");
		}
		if ((days > 0) || (hours > 0)) {
			sb.append(hours).append("小时");
		}
		if ((days > 0) || (hours > 0) || (min > 0)) {
			sb.append(min).append("分");
		}
		if ((days > 0) || (hours > 0) || (min > 0) || (sec > 0)) {
			sb.append(sec).append("秒");
		}*/
		/*
		 * StringBuffer sb = new StringBuffer(
		 * getString(R.string.seckill_time_remain)); if (days > 0) {
		 * sb.append(days).append(getString(R.string.seckill_time_day)); } if
		 * ((days > 0) || (hours > 0)) {
		 * sb.append(hours).append(getString(R.string.seckill_time_hour)); } if
		 * ((days > 0) || (hours > 0) || (min > 0)) {
		 * sb.append(min).append(getString(R.string.seckill_time_min)); } if
		 * ((days > 0) || (hours > 0) || (min > 0) || (sec > 0)) {
		 * sb.append(sec).append(getString(R.string.seckill_time_sec)); }
		 */
		//GameLog.log("时间戳的长度 【 "+second+" 】 最后的时间是："+sb.toString());

        return sb.toString();
    }
}