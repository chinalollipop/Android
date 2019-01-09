package com.cfcp.a01.http.util;

import java.util.Collection;
import java.util.Map;

/**
 * 辅助判断
 * 
 * @author mty
 * @date 2013-6-10下午5:50:57
 */
public class Check {

	public static boolean isEmpty(CharSequence str) {
		return isNull(str) || str.length() == 0;
	}

	public static boolean isEmpty(Object[] os) {
		return isNull(os) || os.length == 0;
	}

	public static boolean isEmpty(Collection<?> l) {
		return isNull(l) || l.isEmpty();
	}

	public static boolean isEmpty(Map<?, ?> m) {
		return isNull(m) || m.isEmpty();
	}

	public static boolean isNull(Object o) {
		return o == null;
	}

	public static boolean isNumericNull(String o) {
		return isEmpty(o)||o.equals("0")||o.equals("0.0")||o.equals("0.00")||o.equals("0.000");
	}

	public static boolean isDigitOnly(CharSequence str)
	{
		if(null == str)
		{
			return false;
		}

		for(char achar : str.toString().toCharArray())
		{
			if(!Character.isDigit(achar))
			{
				return false;
			}
		}
		return true;
	}
}
