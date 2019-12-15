package com.hgapp.a6668.common.http.util;

import java.io.UnsupportedEncodingException;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

/**
 * 
 * @author Michael 2017年5月30日 下午4:07:26
 * @since 1.0
 * @see
 */
public class Md5Utils {
	private static final String MD5_ALGORITHM = "MD5";
	private static final String SHA1_ALGORITHM = "SHA-1";
	private static ThreadLocal<MessageDigest> md5Local = new ThreadLocal<MessageDigest>();
	
	/**
	 * Get the md5 of the given key.
	 */
	public static byte[] computeMd5(String k) {
		MessageDigest md5 = md5Local.get();
		if (md5 == null) {
			try {
				md5 = MessageDigest.getInstance(MD5_ALGORITHM);
				md5Local.set(md5);
			} catch (NoSuchAlgorithmException e) {
				throw new RuntimeException("MD5 not supported", e);
			}
		}
		md5.reset();
		md5.update(getBytes(k));
		return md5.digest();
	}

	public static String getMd5(String k) {
		//Timber.d("before md5:"+k);
		byte[] md5bytes = computeMd5(k);
		return HexString.byteToHexString(md5bytes);
	}

	public static final byte[] getBytes(String k) {
		if (k == null || k.length() == 0) {
			throw new IllegalArgumentException("Key must not be blank");
		}
		try {
			return k.getBytes("utf-8");
		} catch (UnsupportedEncodingException e) {
			throw new RuntimeException(e);
		}
	}

}
