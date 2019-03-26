/**
 * 
 */
package com.cfcp.a01.common.http.request;

import java.io.Serializable;

/**
 * 定义移动端 应答报文中特有的属性，并根据《移动端接入手册》标记序列化与否
 * @author Michael 2017年5月10日 上午11:51:50
 * @since 1.0
 * @see
 */
public class AppTextMessageResponse<T> implements Serializable{

	private T data;

	/**
	 *
	 */
	private static final long serialVersionUID = 4576543547832198559L;
	//private String encryptType;
	private String sign;
	private long timestamp;


	private String errno;
	private String error;

	public AppTextMessageResponse() {
	}

	public void setErrno(String errno) {
		this.errno = errno;
	}

	public String getErrno() {
		return this.errno;
	}
	public String getiStatus()
	{
		try
		{
			return errno;
		}
		catch (NumberFormatException e)
		{

		}
		return "400";
	}
	public String getDescribe() {
		return error;
	}
	public void setError(String error) {
		this.error = error;
	}

	/**
	 * @return the encryptType
	 */
	/*public String getEncryptType() {
		return encryptType;
	}*/
	/**
	 * @param encryptType the encryptType to set
	 */
	/*public void setEncryptType(String encryptType) {
		this.encryptType = encryptType;
	}*/
	/**
	 * @return the sign
	 */
	public String getSign() {
		return sign;
	}
	/**
	 * @param sign the sign to set
	 */
	public void setSign(String sign) {
		this.sign = sign;
	}
	/**
	 * @return the timestamp
	 */
	public long getTimestamp() {
		return timestamp;
	}
	/**
	 * @param timestamp the timestamp to set
	 */
	public void setTimestamp(long timestamp) {
		this.timestamp = timestamp;
	}

	@Override
	public String toString() {//encryptType=" + getEncryptType()+ ",
		return "AppTextMessageResponse [sign=" + getSign() + ", timestamp=" + getTimestamp() + ", data=" + getData()
				+ ", status=" + getErrno() + ",desc:" + getDescribe()+ "]";
	}

	/**
	 * @return the data
	 */
	public T getData() {
		return data;
	}

	/**
	 * @param data the data to set
	 */
	public void setData(T data) {
		this.data = data;
	}

	public boolean isSuccess()
	{
		return "0".equals(getErrno());
	}
	public void setSuccess()
	{
		setErrno("200");
	}
}