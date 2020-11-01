package com.hgapp.betnew.common.util;

import java.util.Date;
import java.util.GregorianCalendar;

public class DateHelper2 {

    public   static  String begin = "" ;
    public   static  String end = "" ;
    public   static  String now = new  java.sql.Date( new Date().getTime()).toString();
    public   static   void  main(String[] args)  {

        // 今天
        calcToday(begin,end,now, new GregorianCalendar());
        // 昨天
        calcYesterday(begin,end,now, new  GregorianCalendar());
        // 本周
        calcThisWeek(begin,end,now, new  GregorianCalendar());
        // 上周
        calcLastWeek(begin,end,now, new  GregorianCalendar());
        // 本月
        calcThisMonth(begin,end,now, new  GregorianCalendar());
        // 上月
        calcLastMonth(begin,end,now, new  GregorianCalendar());
    }
    public   static   void  calcToday(String begin,String end,String now,GregorianCalendar calendar) {

        begin = now;
        end = now;
        System.out.println( " begin: " + begin);
        System.out.println( " end: " + end);
        System.out.println( " ---------------------- " );
    }

    public   static   void  calcYesterday(String begin,String end,String now,GregorianCalendar calendar) {


        calendar.add(GregorianCalendar.DATE,  - 1 );
        begin = new  java.sql.Date(calendar.getTime().getTime()).toString();
        end = begin;
        System.out.println( " begin: " + begin);
        System.out.println( " end: " + end);
        System.out.println( " ---------------------- " );
    }

    public   static   void  calcThisWeek(String begin,String end,String now,GregorianCalendar calendar) {
        end = now;
        int  minus = calendar.get(GregorianCalendar.DAY_OF_WEEK) - 2 ;
        if (minus < 0 ) {
            System.out.println( " 本周还没有开始，请查询上周 " );
            System.out.println( " ---------------------- " );
        } else {

            calendar.add(GregorianCalendar.DATE,  - minus);
            begin = new  java.sql.Date(calendar.getTime().getTime()).toString();
            System.out.println( " begin: " + begin);
            System.out.println( " end: " + end);
            System.out.println( " ---------------------- " );
        }
    }

    public   static   void  calcLastWeek(String begin,String end,String now,GregorianCalendar calendar) {
        int  minus = calendar.get(GregorianCalendar.DAY_OF_WEEK) + 1 ;
        calendar.add(GregorianCalendar.DATE, - minus);
        end = new  java.sql.Date(calendar.getTime().getTime()).toString();
        calendar.add(GregorianCalendar.DATE, - 4 );
        begin = new  java.sql.Date(calendar.getTime().getTime()).toString();
        System.out.println( " begin: " + begin);
        System.out.println( " end: " + end);
        System.out.println( " ---------------------- " );
    }

    public   static   void  calcThisMonth(String begin,String end,String now,GregorianCalendar calendar) {
        end = now;
        int  dayOfMonth = calendar.get(GregorianCalendar.DATE);
        calendar.add(GregorianCalendar.DATE,  - dayOfMonth + 1 );
        begin = new  java.sql.Date(calendar.getTime().getTime()).toString();
        System.out.println( " begin: " + begin);
        System.out.println( " end: " + end);
        System.out.println( " ---------------------- " );
    }
    public   static   void  calcLastMonth(String begin,String end,String now,GregorianCalendar calendar) {

        calendar.set(calendar.get(GregorianCalendar.YEAR),calendar.get(GregorianCalendar.MONTH), 1 );
        calendar.add(GregorianCalendar.DATE,  - 1 );
        end = new  java.sql.Date(calendar.getTime().getTime()).toString();

        int  month = calendar.get(GregorianCalendar.MONTH) + 1 ;
        begin = calendar.get(GregorianCalendar.YEAR) + " - " + month + " -01 " ;

        System.out.println( " begin: " + begin);
        System.out.println( " end: " + end);
        System.out.println( " ---------------------- " );
    }
}
