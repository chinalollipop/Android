package com.hg3366.a3366.common.util;

/**
 * Created by NWF_AK on 2017/7/20.
 */

public class Numeric {
    private static final String UNIT = "万千佰拾亿千佰拾万千佰拾圆角分";
    private static final String DIGIT = "零壹贰叁肆伍陆柒捌玖";
    private static final double MAX_VALUE = 9999999999999.99D;

    /**
     * 数值转换(数字转中文大写)
     * @param v
     * @return
     */
    public static String change(double v) {
        if (v < 0 || v > MAX_VALUE){
            return "参数非法!";
        }
        long l = Math.round(v * 100);
        if (l == 0){
            return "零元整";
        }
        String strValue = l + "";
        // i用来控制数
        int i = 0;
        // j用来控制单位
        int j = UNIT.length() - strValue.length();
        String rs = "";
        boolean isZero = false;
        for (; i < strValue.length(); i++, j++) {
            char ch = strValue.charAt(i);
            if (ch == '0') {
                isZero = true;
                if (UNIT.charAt(j) == '亿' || UNIT.charAt(j) == '万' || UNIT.charAt(j) == '圆') {
                    rs = rs + UNIT.charAt(j);
                    isZero = false;
                }
            } else {
                if (isZero) {
                    rs = rs + "零";
                    isZero = false;
                }
                rs = rs + DIGIT.charAt(ch - '0') + UNIT.charAt(j);
            }
        }
        if (!rs.endsWith("分")) {
            rs = rs + "整";
        }
        rs = rs.replaceAll("亿万", "亿");
        return rs;
    }

    /*public static void main(String[] args){
        System.out.println(Numeric.change(12356789.9845));
    }*/
}
