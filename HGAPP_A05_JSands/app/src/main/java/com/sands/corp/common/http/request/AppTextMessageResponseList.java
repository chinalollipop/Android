/**
 * 
 */
package com.sands.corp.common.http.request;

import java.io.Serializable;
import java.util.List;

/**
 * 主要应对服务器返回的数据都是数组
 * @author Daniel 2018年8月30日 上午11:51:50
 * @since 1.0
 * @see
 */
public class AppTextMessageResponseList<T> implements Serializable{

	private List<T> data;

	/**
	 *
	 */
	private static final long serialVersionUID = 4576543547832198559L;
	//private String encryptType;
	private String sign;
	private long timestamp;


	private String status;
	private String describe;

	public AppTextMessageResponseList() {
	}

	public void setStatus(String status) {
		this.status = status;
	}

	public String getStatus() {
		return this.status;
	}
	public String getiStatus()
	{
		try
		{
			return status;
		}
		catch (NumberFormatException e)
		{

		}
		return "400";
	}
	public String getDescribe() {
		return describe;
	}
	public void setDescribe(String describe) {
		this.describe = describe;
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
				+ ", status=" + getStatus() + ",desc:" + getDescribe()+ "]";
	}

	/**
	 * @return the data
	 */
	public List<T> getData() {
		return data;
	}

	/**
	 * @param data the data to set
	 */
	public void setData(List<T> data) {
		this.data = data;
	}

	public boolean isSuccess()
	{
		return "200".equals(getStatus());
	}
	public void setSuccess()
	{
		setStatus("200");
	}
}
