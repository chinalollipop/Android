/**
 * 
 */
package com.hgapp.m8.common.http.util;

/**
 * @author Michael 2017年5月30日 下午4:55:57
 * @since 1.0
 * @see
 */
public class ByteUtil {

	public static String byte2Hex(byte[] b) {
		StringBuffer sb = new StringBuffer();
		String tmp = "";
		for (int i = 0; i < b.length; i++) {
			tmp = Integer.toHexString(b[i] & 0XFF);
			if (tmp.length() == 1)
				sb.append("0");
			sb.append(tmp);
		}
		return sb.toString().toUpperCase();
	}
	
}
