package com.sunapp.bloc.common.http.util;


import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.serializer.SerializerFeature;
import com.sunapp.common.util.Check;


/**
 * 消息转换的工具类
 * @author Michael 2017年5月31日 上午9:26:56
 * @since 1.0
 * @see
 */
public class AppTextMessageConvert {

	public final static SerializerFeature[] SERIALIZER_FEATURE = {
			SerializerFeature.WriteMapNullValue,
			SerializerFeature.WriteNullListAsEmpty,
			SerializerFeature.WriteNullStringAsEmpty,
			SerializerFeature.WriteNullNumberAsZero
			};

	/**
	 * 将给定的cls转换为加密的字符串
	 * @param cls	待转换的对象
	 * @param key	转换过程所需的3des 私有key
	 * @return 转换后的加密字符串
	 */
	public static String toEncryptText(Object cls, String key) {
		if (cls == null || Check.isEmpty(key)) {
			throw new RuntimeException("转换对象时发生异常：参数 'cls'、'key'不可以为空" );
		}

		if (cls instanceof java.io.Serializable) {
			String plainText = JSON.toJSONString(cls, SERIALIZER_FEATURE);
			return Des3Util.encrypt(plainText, key);
		}
		throw new RuntimeException("转换对象时发生异常：'cls'对象必须是java.io.Serializable的子类" );
	}

	/**
	 * 解密并转换data value为指定的T object.
	 * @param T		转换后的目标对象
	 * @param data	待转换的加密对象，对于当前项目这里均为字符串
	 * @param key	解密过程所需的3des 私有key
	 * @return 转换后的对象
	 */
	public static <T> T toPlainObject(Class<T> T, String data, String key){
		if (Check.isEmpty(data) ||  Check.isEmpty(key)) {
			throw new RuntimeException("转换对象时发生异常：参数'data'、'key'不可以为空" );
		}

		String plainText = Des3Util.decrypt(data, key);
		if (plainText != null) {
			return (T) JSON.parseObject(plainText, T);
		}
		return null;
	}
}
