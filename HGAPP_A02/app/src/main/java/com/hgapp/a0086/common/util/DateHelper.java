package com.hgapp.a0086.common.util;


import java.text.ParsePosition;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.GregorianCalendar;

public class DateHelper {

    private static final SimpleDateFormat FORMATE = new SimpleDateFormat("yyyy-MM-dd");

// 用来全局控制 上一周，本周，下一周的周数变化

    public static void main(String[] args) {
        System.out.println(getMondayPlus());
        System.out.println("获取当天日期:"+getToday());
        System.out.println("获取昨天日期:"+getYesterday());
        System.out.println("获取本周一日期:"+getCurrentWeekDayBegin());
        System.out.println("获取本周日的日期~:"+getCurrentWeekDayEnd());
        System.out.println("获取上周一日期:"+getPreviousWeekDayBegin());
        System.out.println("获取上周日日期:"+getPreviousWeekDayEnd());
        System.out.println("获取下周一日期:"+getNextMonday());
        System.out.println("获取下周日日期:"+getNextSunday());
        System.out.println("获得相应周的周六的日期:"+getSaturday());
        System.out.println("获取本月第一天日期:"+getCurrentMonthDayBegin());
        System.out.println("获取本月最后一天日期:"+getCurrentMonthDayEnd());
        System.out.println("获取上月第一天日期:"+getPreviousMonthDayBegin());
        System.out.println("获取上月最后一天的日期:"+getPreviousMonthDayEnd());
        System.out.println("获取下月第一天日期:"+getNextMonthFirst());
        System.out.println("获取下月最后一天日期:"+getNextMonthEnd());
        System.out.println("获取本年的第一天日期:"+getCurrentYearFirst());
        System.out.println("获取本年最后一天日期:"+getCurrentYearEnd());
        System.out.println("获取去年的第一天日期:"+getPreviousYearFirst());
        System.out.println("获取去年的最后一天日期:"+getPreviousYearEnd());
        System.out.println("获取明年第一天日期:"+getNextYearFirst());
        System.out.println("获取明年最后一天日期:"+getNextYearEnd());
        System.out.println("获取本季度第一天到最后一天:"+getThisSeasonTime());
        System.out.println("获取本季度第一天到最后一天:"+getLastSeasonTime());
    }

    /**
     * 得到二个日期间的间隔天数
     */
    public static String getTwoDay(String sj1, String sj2) {
        long day = 0;
        try {
            Date date = FORMATE.parse(sj1);
            Date mydate = FORMATE.parse(sj2);
            day = (date.getTime() - mydate.getTime()) / (24 * 60 * 60 * 1000);
        } catch (Exception e) {
            return "";
        }
        return day + "";
    }

    /**
     * 根据一个日期，返回是星期几的字符串
     *
     * @param sdate
     * @return
     */
    public static String getWeek(String sdate) {
        // 再转换为时间
        Date date = strToDate(sdate);
        Calendar c = Calendar.getInstance();
        c.setTime(date);
        // int hour=c.get(Calendar.DAY_OF_WEEK);
        // hour中存的就是星期几了，其范围 1~7
        // 1=星期日 7=星期六，其他类推
        return new SimpleDateFormat("EEEE").format(c.getTime());
    }

    /**
     * 将短时间格式字符串转换为时间 yyyy-MM-dd
     *
     * @param strDate
     * @return
     */
    public static Date strToDate(String strDate) {
        ParsePosition pos = new ParsePosition(0);
        Date strtodate = FORMATE.parse(strDate, pos);
        return strtodate;
    }

    // 上月第一天
    public static String getPreviousMonthDayBegin() {
        Calendar lastDate = Calendar.getInstance();
        lastDate.set(Calendar.DATE, 1);// 设为当前月的1号
        lastDate.add(Calendar.MONTH, -1);// 减一个月，变为上月的1号
        return FORMATE.format(lastDate.getTime());
    }

    // 获得上月最后一天的日期
    public static String getPreviousMonthDayEnd() {
        String str = "";
        Calendar lastDate = Calendar.getInstance();
        lastDate.add(Calendar.MONTH, -1);// 减一个月
        lastDate.set(Calendar.DATE, 1);// 把日期设置为当月第一天
        lastDate.roll(Calendar.DATE, -1);// 日期回滚一天，也就是本月最后一天
        str = FORMATE.format(lastDate.getTime());
        return str;
    }

    // 获取当月第一天
    public static String getCurrentMonthDayBegin() {
        Calendar lastDate = Calendar.getInstance();
        lastDate.set(Calendar.DATE, 1);// 设为当前月的1号
        return FORMATE.format(lastDate.getTime());
    }

    // 计算当月最后一天,返回字符串
    public static String getCurrentMonthDayEnd() {
        Calendar lastDate = Calendar.getInstance();
        lastDate.set(Calendar.DATE, 1);// 设为当前月的1号
        lastDate.add(Calendar.MONTH, 1);// 加一个月，变为下月的1号
        lastDate.add(Calendar.DATE, -1);// 减去一天，变为当月最后一天
        return FORMATE.format(lastDate.getTime());
    }

    // 获取当天时间
    public static String getToday() {
        Date now = new Date();
        String hehe = FORMATE.format(now);
        return hehe;
    }

    // 获取当天时间
    public static String getYesterday() {
        Calendar cd = Calendar.getInstance();
        cd.add(Calendar.DAY_OF_YEAR, -1);
        String hehe = FORMATE.format(cd.getTime());
        return hehe;
    }

    // 获取当天的前天时间
    public static String getYesterday2() {
        Calendar cd = Calendar.getInstance();
        cd.add(Calendar.DAY_OF_YEAR, -2);
        String hehe = FORMATE.format(cd.getTime());
        return hehe;
    }
    // 获取上周时间
    public static String getLastWeek2() {
        Calendar cd = Calendar.getInstance();
        cd.roll(Calendar.DAY_OF_YEAR, -8);
        String hehe = FORMATE.format(cd.getTime());
        return hehe;
    }

    // 获取上周时间
    public static String getLastWeek() {
        Calendar cd = Calendar.getInstance();
        cd.roll(Calendar.DAY_OF_YEAR, -7);
        String hehe = FORMATE.format(cd.getTime());
       /* GregorianCalendar currentDate = new GregorianCalendar();
        currentDate.roll(Calendar.DATE,-7);
        Date monday = currentDate.getTime();
        */
       /* Calendar cal = Calendar.getInstance();
        SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd");
        cal.set(Calendar.DAY_OF_WEEK, Calendar.MONDAY); // 获取本周一的日期
        System.out.println(df.format(cal.getTime()));
        cal.set(Calendar.DAY_OF_WEEK, Calendar.SUNDAY);// 这种输出的是上个星期周日的日期，因为老外那边把周日当成第一天
        cal.add(Calendar.WEEK_OF_YEAR, 1);// 增加一个星期，才是我们中国人理解的本周日的日期
        String hehe = FORMATE.format(cal.getTime());
        System.out.println(df.format(cal.getTime()));*/
        return hehe;
    }

    // 获取下周时间
    public static String getNextWeek() {
        Calendar cd = Calendar.getInstance();
        cd.add(Calendar.DAY_OF_YEAR, 7);
        String hehe = FORMATE.format(cd.getTime());
        return hehe;
    }

    // 获取本季度时间
    public static String getCurrentSeason() {
        Calendar cd = Calendar.getInstance();
        cd.add(Calendar.DAY_OF_YEAR, 7);
        String hehe = FORMATE.format(cd.getTime());
        return hehe;
    }

    // 获取半年时间
    public static String getHalfYear() {
        Calendar cd = Calendar.getInstance();
        cd.add(Calendar.DAY_OF_YEAR, 7);
        String hehe = FORMATE.format(cd.getTime());
        return hehe;
    }

    // 获取当天时间
    public static String getNowTime(String dateformat) {
        Date now = new Date();
        SimpleDateFormat dateFormat = new SimpleDateFormat(dateformat);// 可以方便地修改日期格式
        String hehe = dateFormat.format(now);
        return hehe;
    }

    /**
     * 得到本月第一天的日期
     * @Methods Name getFirstDayOfMonth
     * @return Date
     */
    public Date getFirstDayOfMonth(Date date)   {
        Calendar cDay = Calendar.getInstance();
        cDay.setTime(date);
        cDay.set(Calendar.DAY_OF_MONTH, 1);
        System.out.println(cDay.getTime());
        return cDay.getTime();
    }
    /**
     * 得到本月最后一天的日期
     * @Methods Name getLastDayOfMonth
     * @return Date
     */
    public Date getLastDayOfMonth(Date date)   {
        Calendar cDay = Calendar.getInstance();
        cDay.setTime(date);
        cDay.set(Calendar.DAY_OF_MONTH, cDay.getActualMaximum(Calendar.DAY_OF_MONTH));
        System.out.println(cDay.getTime());
        return cDay.getTime();
    }



    // 获得当前日期与本周一相差的天数
    private static int getMondayPlus() {
        Calendar cd = Calendar.getInstance();
        // 获得今天是一周的第几天，星期日是第一天，星期二是第二天......
        int dayOfWeek = cd.get(Calendar.DAY_OF_WEEK) - 1; // 因为按中国礼拜一作为第一天所以这里减1
        if (dayOfWeek == 1) {
            return 0;
        } else {
            return 1 - dayOfWeek;
        }
    }

    // 获得本周一的日期
    public static String getCurrentWeekDayBegin() {
        int mondayPlus = getMondayPlus();
        GregorianCalendar currentDate = new GregorianCalendar();
        currentDate.add(GregorianCalendar.DATE, mondayPlus);
        Date monday = currentDate.getTime();
        String preMonday = FORMATE.format(monday);
        return preMonday;
    }

    // 获得本周星期日的日期
    public static String getCurrentWeekDayEnd() {
        int mondayPlus = getMondayPlus();
        GregorianCalendar currentDate = new GregorianCalendar();
        currentDate.add(GregorianCalendar.DATE, mondayPlus + 6);
        Date monday = currentDate.getTime();
        String preMonday = FORMATE.format(monday);
        return preMonday;
    }

    // 获得相应周的周六的日期
    public static String getSaturday() {
        int mondayPlus = getMondayPlus();
        GregorianCalendar currentDate = new GregorianCalendar();
        currentDate.add(GregorianCalendar.DATE, mondayPlus + 5);
        Date monday = currentDate.getTime();
        String preMonday = FORMATE.format(monday);
        return preMonday;
    }

    // 获得上周星期一的日期
    public static String getPreviousWeekDayBegin() {
        int mondayPlus = getMondayPlus();
        GregorianCalendar currentDate = new GregorianCalendar();
        currentDate.add(GregorianCalendar.DATE, mondayPlus - 7);
        Date monday = currentDate.getTime();
        String preMonday = FORMATE.format(monday);
        return preMonday;
    }

    // 获得上周星期日的日期
    public static String getPreviousWeekDayEnd() {
        int mondayPlus = getMondayPlus();
        GregorianCalendar currentDate = new GregorianCalendar();
        currentDate.add(GregorianCalendar.DATE, mondayPlus - 1);
        Date monday = currentDate.getTime();
        String preMonday = FORMATE.format(monday);
        return preMonday;
    }

    // 获得下周星期一的日期
    public static String getNextMonday() {
        int mondayPlus = getMondayPlus();
        GregorianCalendar currentDate = new GregorianCalendar();
        currentDate.add(GregorianCalendar.DATE, mondayPlus + 7);
        Date monday = currentDate.getTime();
        String preMonday = FORMATE.format(monday);
        return preMonday;
    }

    // 获得下周星期日的日期
    public static String getNextSunday() {
        int mondayPlus = getMondayPlus();
        GregorianCalendar currentDate = new GregorianCalendar();
        currentDate.add(GregorianCalendar.DATE, mondayPlus + 7 + 6);
        Date monday = currentDate.getTime();
        String preMonday = FORMATE.format(monday);
        return preMonday;
    }

    private static int getMonthPlus() {
        Calendar cd = Calendar.getInstance();
        int monthOfNumber = cd.get(Calendar.DAY_OF_MONTH);
        cd.set(Calendar.DATE, 1);// 把日期设置为当月第一天
        cd.roll(Calendar.DATE, -1);// 日期回滚一天，也就是最后一天
        int MaxDate = cd.get(Calendar.DATE);
        if (monthOfNumber == 1) {
            return -MaxDate;
        } else {
            return 1 - monthOfNumber;
        }
    }

    // 获得下个月第一天的日期
    public static String getNextMonthFirst() {
        String str = "";
        Calendar lastDate = Calendar.getInstance();
        lastDate.add(Calendar.MONTH, 1);// 减一个月
        lastDate.set(Calendar.DATE, 1);// 把日期设置为当月第一天
        str = FORMATE.format(lastDate.getTime());
        return str;
    }

    // 获得下个月最后一天的日期
    public static String getNextMonthEnd() {
        String str = "";
        Calendar lastDate = Calendar.getInstance();
        lastDate.add(Calendar.MONTH, 1);// 加一个月
        lastDate.set(Calendar.DATE, 1);// 把日期设置为当月第一天
        lastDate.roll(Calendar.DATE, -1);// 日期回滚一天，也就是本月最后一天
        str = FORMATE.format(lastDate.getTime());
        return str;
    }

    // 获得明年最后一天的日期
    public static String getNextYearEnd() {
        String str = "";
        Calendar lastDate = Calendar.getInstance();
        lastDate.add(Calendar.YEAR, 1);// 加一个年
        lastDate.set(Calendar.DAY_OF_YEAR, 1);
        lastDate.roll(Calendar.DAY_OF_YEAR, -1);
        str = FORMATE.format(lastDate.getTime());
        return str;
    }

    // 获得明年第一天的日期
    public static String getNextYearFirst() {
        String str = "";
        Calendar lastDate = Calendar.getInstance();
        lastDate.add(Calendar.YEAR, 1);// 加一个年
        lastDate.set(Calendar.DAY_OF_YEAR, 1);
        str = FORMATE.format(lastDate.getTime());
        return str;

    }

    // 获得本年有多少天
    private static int getMaxYear() {
        Calendar cd = Calendar.getInstance();
        cd.set(Calendar.DAY_OF_YEAR, 1);// 把日期设为当年第一天
        cd.roll(Calendar.DAY_OF_YEAR, -1);// 把日期回滚一天。
        int MaxYear = cd.get(Calendar.DAY_OF_YEAR);
        return MaxYear;
    }

    private static int getYearPlus() {
        Calendar cd = Calendar.getInstance();
        int yearOfNumber = cd.get(Calendar.DAY_OF_YEAR);// 获得当天是一年中的第几天
        cd.set(Calendar.DAY_OF_YEAR, 1);// 把日期设为当年第一天
        cd.roll(Calendar.DAY_OF_YEAR, -1);// 把日期回滚一天。
        int MaxYear = cd.get(Calendar.DAY_OF_YEAR);
        if (yearOfNumber == 1) {
            return -MaxYear;
        } else {
            return 1 - yearOfNumber;
        }
    }

    // 获得本年第一天的日期
    public static String getCurrentYearFirst() {
        int yearPlus = getYearPlus();
        GregorianCalendar currentDate = new GregorianCalendar();
        currentDate.add(GregorianCalendar.DATE, yearPlus);
        Date yearDay = currentDate.getTime();
        String preYearDay = FORMATE.format(yearDay);
        return preYearDay;
    }

    // 获得本年最后一天的日期 *
    public static String getCurrentYearEnd() {
        Date date = new Date();
        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy");// 可以方便地修改日期格式
        String years = dateFormat.format(date);
        return years + "-12-31";
    }

    // 获得上年第一天的日期 *
    public static String getPreviousYearFirst() {
        Date date = new Date();
        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy");// 可以方便地修改日期格式
        String years = dateFormat.format(date);
        int years_value = Integer.parseInt(years);
        years_value--;
        return years_value + "-01-01";
    }

    // 获得上年最后一天的日期
    public static String getPreviousYearEnd() {
        int yearPlus = getYearPlus();
        int MaxYear = 0; //一年最大天数
        GregorianCalendar currentDate = new GregorianCalendar();
        currentDate.add(GregorianCalendar.DATE, yearPlus + MaxYear * (-1)
                + (MaxYear - 1));
        Date yearDay = currentDate.getTime();
        String preYearDay = FORMATE.format(yearDay);
        return preYearDay;
    }

    // 获得本季度
    public static String getThisSeasonTime(int month) {
        int array[][] = { { 1, 2, 3 }, { 4, 5, 6 }, { 7, 8, 9 }, { 10, 11, 12 } };
        int season = 1;
        if (month >= 1 && month <= 3) {
            season = 1;
        }
        if (month >= 4 && month <= 6) {
            season = 2;
        }
        if (month >= 7 && month <= 9) {
            season = 3;
        }
        if (month >= 10 && month <= 12) {
            season = 4;
        }
        int start_month = array[season - 1][0];
        int end_month = array[season - 1][2];

        Date date = new Date();
        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy");// 可以方便地修改日期格式
        String years = dateFormat.format(date);
        int years_value = Integer.parseInt(years);

        String start_days = "01";// years+"-"+String.valueOf(start_month)+"-1";//getLastDayOfMonth(years_value,start_month);
        int end_days = getLastDayOfMonth(years_value, end_month);
        String seasonDate = years_value + "-" + start_month + "-" + start_days
                + ";" + years_value + "-" + end_month + "-" + end_days;
        return seasonDate;
    }

    public static String getThisSeasonTime() {
        Calendar lastDate = Calendar.getInstance();
        //lastDate.add(Calendar.MONTH,1);
        int month =lastDate.get(Calendar.MONTH);
        int array[][] = { { 1, 2, 3 }, { 4, 5, 6 }, { 7, 8, 9 }, { 10, 11, 12 } };
        int season = 1;
        if (month >= 1 && month <= 3) {
            season = 1;
        }
        if (month >= 4 && month <= 6) {
            season = 2;
        }
        if (month >= 7 && month <= 9) {
            season = 3;
        }
        if (month >= 10 && month <= 12) {
            season = 4;
        }
        int start_month = array[season - 1][0];
        int end_month = array[season - 1][2];

        Date date = new Date();
        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy");// 可以方便地修改日期格式
        String years = dateFormat.format(date);
        int years_value = Integer.parseInt(years);

        String start_days = "01";// years+"-"+String.valueOf(start_month)+"-1";//getLastDayOfMonth(years_value,start_month);
        int end_days = getLastDayOfMonth(years_value, end_month);
        String seasonDate = years_value + "-" + start_month + "-" + start_days
                + ";" + years_value + "-" + end_month + "-" + end_days;
        return seasonDate;
    }

    public static String getLastSeasonTime() {
        Calendar lastDate = Calendar.getInstance();
        int month = lastDate.get(Calendar.MONTH);
        int array[][] = { { 1, 2, 3 }, { 4, 5, 6 }, { 7, 8, 9 }, { 10, 11, 12 } };
        int season = 1;
        if (month >= 1 && month <= 3) {
            season = 1;
        }
        if (month >= 4 && month <= 6) {
            season = 2;
        }
        if (month >= 7 && month <= 9) {
            season = 3;
        }
        if (month >= 10 && month <= 12) {
            season = 4;
        }
        season -= 1;
        if (season == 0){
            season = 4;
        }
        int start_month = array[season - 1][0];
        int end_month = array[season - 1][2];

        Date date = new Date();
        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy");// 可以方便地修改日期格式
        String years = dateFormat.format(date);
        int years_value = Integer.parseInt(years);
        if (season == 4){
            years_value -= 1;
        }
        String start_days = "01";// years+"-"+String.valueOf(start_month)+"-1";//getLastDayOfMonth(years_value,start_month);
        int end_days = getLastDayOfMonth(years_value, end_month);
        String seasonDate = years_value + "-" + start_month + "-" + start_days
                + ";" + years_value + "-" + end_month + "-" + end_days;
        return seasonDate;
    }

    public static String getLastSeasonTime(int month) {
        int array[][] = { { 1, 2, 3 }, { 4, 5, 6 }, { 7, 8, 9 }, { 10, 11, 12 } };
        int season = 1;
        if (month >= 1 && month <= 3) {
            season = 1;
        }
        if (month >= 4 && month <= 6) {
            season = 2;
        }
        if (month >= 7 && month <= 9) {
            season = 3;
        }
        if (month >= 10 && month <= 12) {
            season = 4;
        }
        season -= 1;
        if (season == 0){
            season = 4;
        }
        int start_month = array[season - 1][0];
        int end_month = array[season - 1][2];

        Date date = new Date();
        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy");// 可以方便地修改日期格式
        String years = dateFormat.format(date);
        int years_value = Integer.parseInt(years);
        if (season == 4){
            years_value -= 1;
        }
        String start_days = "01";// years+"-"+String.valueOf(start_month)+"-1";//getLastDayOfMonth(years_value,start_month);
        int end_days = getLastDayOfMonth(years_value, end_month);
        String seasonDate = years_value + "-" + start_month + "-" + start_days
                + ";" + years_value + "-" + end_month + "-" + end_days;
        return seasonDate;
    }

    /**
     * 得到本季度第一天的日期
     * @Methods Name getFirstDayOfQuarter
     * @return Date
     */
    public static String getFirstDayOfQuarter(Date date)   {
        Calendar cDay = Calendar.getInstance();
        cDay.setTime(date);
        int curMonth = cDay.get(Calendar.MONTH);
        if (curMonth >= Calendar.JANUARY && curMonth <= Calendar.MARCH){
            cDay.set(Calendar.MONTH, Calendar.JANUARY);
        }
        if (curMonth >= Calendar.APRIL && curMonth <= Calendar.JUNE){
            cDay.set(Calendar.MONTH, Calendar.APRIL);
        }
        if (curMonth >= Calendar.JULY && curMonth <= Calendar.AUGUST) {
            cDay.set(Calendar.MONTH, Calendar.JULY);
        }
        if (curMonth >= Calendar.OCTOBER && curMonth <= Calendar.DECEMBER) {
            cDay.set(Calendar.MONTH, Calendar.OCTOBER);
        }
        cDay.set(Calendar.DAY_OF_MONTH, cDay.getActualMinimum(Calendar.DAY_OF_MONTH));
        System.out.println(cDay.getTime());
        return FORMATE.format(cDay.getTime());
    }
    /**
     * 得到本季度最后一天的日期
     * @Methods Name getLastDayOfQuarter
     * @return Date
     */
    public static String getLastDayOfQuarter(Date date)   {
        Calendar cDay = Calendar.getInstance();
        cDay.setTime(date);
        int curMonth = cDay.get(Calendar.MONTH);
        if (curMonth >= Calendar.JANUARY && curMonth <= Calendar.MARCH){
            cDay.set(Calendar.MONTH, Calendar.MARCH);
        }
        if (curMonth >= Calendar.APRIL && curMonth <= Calendar.JUNE){
            cDay.set(Calendar.MONTH, Calendar.JUNE);
        }
        if (curMonth >= Calendar.JULY && curMonth <= Calendar.AUGUST) {
            cDay.set(Calendar.MONTH, Calendar.AUGUST);
        }
        if (curMonth >= Calendar.OCTOBER && curMonth <= Calendar.DECEMBER) {
            cDay.set(Calendar.MONTH, Calendar.DECEMBER);
        }
        cDay.set(Calendar.DAY_OF_MONTH, cDay.getActualMaximum(Calendar.DAY_OF_MONTH));
        System.out.println(cDay.getTime());
        return FORMATE.format(cDay.getTime());
    }


    /**
     * 获取某年某月的最后一天
     *
     * @param year
     *            年
     * @param month
     *            月
     * @return 最后一天
     */
    private static int getLastDayOfMonth(int year, int month) {
        if (month == 1 || month == 3 || month == 5 || month == 7 || month == 8
                || month == 10 || month == 12) {
            return 31;
        }
        if (month == 4 || month == 6 || month == 9 || month == 11) {
            return 30;
        }
        if (month == 2) {
            if (isLeapYear(year)) {
                return 29;
            } else {
                return 28;
            }
        }
        return 0;
    }

    /**
     * 是否闰年
     *
     * @param year
     *            年
     * @return
     */
    public static boolean isLeapYear(int year) {
        return (year % 4 == 0 && year % 100 != 0) || (year % 400 == 0);
    }

}
