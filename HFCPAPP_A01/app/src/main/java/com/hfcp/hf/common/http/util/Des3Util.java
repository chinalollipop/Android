package com.hfcp.hf.common.http.util;

import java.security.Key;

import javax.crypto.Cipher;
import javax.crypto.SecretKeyFactory;
import javax.crypto.spec.DESedeKeySpec;
import javax.crypto.spec.IvParameterSpec;

/**
 * 
 * @author Daniel 2017年5月30日 下午4:44:38
 * @since 1.0
 * @see
 */
public class Des3Util {
	// 向量
	private final static String IV = "yr3OMKu8";
	private final static String CBC = "/CBC/PKCS5Padding";
	private final static String ECB = "/ECB/PKCS5Padding";
	private final static String ALGORITHM = "DESede";
	// 加解密统一使用的编码方式
	private final static String ENCODING = "utf-8";
	//private static final Base64 BASE64 = new Base64();

	/**
	 * 3DES加密
	 * 
	 * @param plainText
	 * @param key
	 * @return
	 * @throws Exception
	 */
	public static String encrypt(String plainText, String key) {
		try {
			Cipher cipher = getCipher(Cipher.ENCRYPT_MODE, key);

			// 通过dofinal方法，实现加解密。通过base64算法进行格式化
			byte[] encryptData = cipher.doFinal(plainText.getBytes(ENCODING));
			return android.util.Base64.encodeToString(encryptData, android.util.Base64.DEFAULT);
			//return BASE64.encodeAsString(encryptData);
		} catch (Exception e) {
			// TODO: handle exception
			throw new RuntimeException("3DES加密过程异常", e);
		}
	}


	/**
	 * 3DES解密
	 * 
	 * @param encryptText
	 *            加密文本
	 * @return
	 * @throws Exception
	 */
	public static String decrypt(String encryptText, String key) {
		try {
			Cipher cipher = getCipher(Cipher.DECRYPT_MODE, key);

			// 通过dofinal方法，实现加解密。通过base64算法进行格式化
			byte[] decryptData = cipher.doFinal(android.util.Base64.decode(encryptText,android.util.Base64.DEFAULT));
			//byte[] decryptData = cipher.doFinal(BASE64.decode(encryptText));
			return new String(decryptData, ENCODING);
		} catch (Exception e) {
			// TODO: handle exception
			throw new RuntimeException("3DES解密过程异常", e);
		}
	}
	
	private static Cipher getCipher(int encryptMode, String key) {
		// TODO Auto-generated method stub
		try {
			// 通过约定的秘钥和算法，构建秘钥对象deskey
			DESedeKeySpec keySpec = new DESedeKeySpec(key.getBytes(ENCODING));
			SecretKeyFactory keyfactory = SecretKeyFactory.getInstance(ALGORITHM);
			Key deskey = keyfactory.generateSecret(keySpec);

			// 根据秘钥对象和向量，实例化cipher对象，负责加解密
			Cipher cipher = Cipher.getInstance(ALGORITHM + CBC);
			IvParameterSpec ips = new IvParameterSpec(IV.getBytes(ENCODING));
			cipher.init(encryptMode, deskey, ips);
			
			return cipher;
		} catch (Exception e) {
			// TODO: handle exception
			throw new RuntimeException("获取Cipher时发生异常", e);
		}
	}

	public static void main(String[] args) {
		// 加密
		String decrptStr;
		try {
			decrptStr = encrypt("Michael", "1a0dcc06af4585e83a1c4967");
			System.out.println(decrptStr);
			// 解密
			System.out.println(decrypt(decrptStr, "1a0dcc06af4585e83a1c4967"));
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

	}

}