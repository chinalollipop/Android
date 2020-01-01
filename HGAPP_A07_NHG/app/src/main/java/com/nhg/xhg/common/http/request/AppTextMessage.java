/**
 * 
 */
package com.nhg.xhg.common.http.request;

import com.alibaba.fastjson.annotation.JSONField;

/**
 * 定义移动端 请求与应答报文中公用的属性
 * @author Michael 2017年5月10日 上午11:32:06
 * @since 1.0
 * @see
 */
public class AppTextMessage extends TextMessage {

	/**
	 * 
	 */
	private static final long serialVersionUID = 4576543547832198559L;
	@JSONField(serialize = false, deserialize = false)
	private String seqId;
	private String encryptType;
	private String mac;
	private long timestamp;

	
	/**
	 * @return the seqId
	 */
	public String getSeqId() {
		return seqId;
	}
	/**
	 * @param seqId the seqId to set
	 */
	public void setSeqId(String seqId) {
		this.seqId = seqId;
	}
	/**
	 * @return the encryptType
	 */
	public String getEncryptType() {
		return encryptType;
	}
	/**
	 * @param encryptType the encryptType to set
	 */
	public void setEncryptType(String encryptType) {
		this.encryptType = encryptType;
	}
	/**
	 * @return the mac
	 */
	public String getMac() {
		return mac;
	}
	/**
	 * @param mac the mac to set
	 */
	public void setMac(String mac) {
		this.mac = mac;
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
	

	
}
