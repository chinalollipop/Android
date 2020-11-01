package com.hgapp.betnew.common.util;

import com.hgapp.common.util.GameLog;

import java.util.ArrayList;
import java.util.List;

public class CombineUtils {

    /**
     * 从n个数字中选择m个数字
     *
     * @param a
     * @param m
     * @return
     */
    public static List combine(int[] a, int m) throws Exception {
        int n = a.length;
        if (m > n) {
            throw new Exception("错误！数组a中只有" + n + "个元素。" + m + "大于" + 2 + "!!!");
        }

        List result = new ArrayList();

        int[] bs = new int[n];
        for (int i = 0;
             i < n;
             i++) {
            bs[i] = 0;
        }
        //初始化
        for (int i = 0;
             i < m;
             i++) {
            bs[i] = 1;
        }
        boolean flag = true;
        boolean tempFlag = false;
        int pos = 0;
        int sum = 0;
        //首先找到第一个10组合，然后变成01，同时将左边所有的1移动到数组的最左边
        do {
            sum = 0;
            pos = 0;
            tempFlag = true;
            result.add(print(bs, a, m));

            for (int i = 0;
                 i < n - 1;
                 i++) {
                if (bs[i] == 1 && bs[i + 1] == 0) {
                    bs[i] = 0;
                    bs[i + 1] = 1;
                    pos = i;
                    break;
                }
            }
            //将左边的1全部移动到数组的最左边

            for (int i = 0;
                 i < pos;
                 i++) {
                if (bs[i] == 1) {
                    sum++;
                }
            }
            for (int i = 0;
                 i < pos;
                 i++) {
                if (i < sum) {
                    bs[i] = 1;
                } else {
                    bs[i] = 0;
                }
            }

            //检查是否所有的1都移动到了最右边
            for (int i = n - m;
                 i < n;
                 i++) {
                if (bs[i] == 0) {
                    tempFlag = false;
                    break;
                }
            }
            if (tempFlag == false) {
                flag = true;
            } else {
                flag = false;
            }

        } while (flag);
        result.add(print(bs, a, m));

        return result;
    }

    private static int[] print(int[] bs, int[] a, int m) {
        int[] result = new int[m];
        int pos = 0;
        for (int i = 0;
             i < bs.length;
             i++) {
            if (bs[i] == 1) {
                result[pos] = a[i];
                pos++;
            }
        }
        return result;
    }

    public static void print(List l) {
        for (int i = 0;
             i < l.size(); i++) {
            int[] a = (int[]) l.get(i);
            for (int j = 0; j < a.length; j++) {
                GameLog.log(a[j] + "/t");
            }
        }
    }
}
