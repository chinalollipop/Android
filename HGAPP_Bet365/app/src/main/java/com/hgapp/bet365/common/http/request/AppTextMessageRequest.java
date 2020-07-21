/**
 * 
 */
package com.hgapp.bet365.common.http.request;

import com.alibaba.fastjson.annotation.JSONField;

import java.util.Map;

/**
 * 定义移动端 请求报文中特有的属性，并根据《移动端接入手册》标记序列化与否、以及验证器
 * @author Michael 2017年5月10日 上午11:49:26
 * @since 1.0
 * @see
 */
public class AppTextMessageRequest<T> extends AppTextMessage {

	/**
	 * 
	 */
	private static final long serialVersionUID = -1876876866635372625L;

	private String version;

	private String appRefer;

	private String digiSign;

	private String pid;

	private String token;
	private T data;

	//added by AK
	private String channelID;

	//added by nereus
	private String deviceId;
	//added by nereus
	private String locale;

	/**
	 * @return the channelID
	 */
	public String getChannelID() {
		return channelID;
	}

	/**
	 * @param channelID
	 *            the channelID to set
	 */
	public void setChannelID(String channelID) {
		this.channelID = channelID;
	}

	public String getLocale() {
		return locale;
	}

	public void setLocale(String locale) {
		this.locale = locale;
	}

	public String getDeviceId() {
		return deviceId;
	}

	public void setDeviceId(String deviceId) {
		this.deviceId = deviceId;
	}

	/**
	 * @return the token
	 */
	public String getToken() {
		return token;
	}

	/**
	 * @param token
	 *            the token to set
	 */
	public void setToken(String token) {
		this.token = token;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see com.pn.px.commons.messages.AbstractMessage#getId()
	 */
	@Override
	@JSONField(serialize = false)
	public String getId() {
		// TODO Auto-generated method stub
		return super.getId();
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see com.pn.px.commons.messages.AbstractMessage#getProperty()
	 */
	@Override
	@JSONField(serialize = false)
	public Map<String, Object> getProperty() {
		// TODO Auto-generated method stub
		return super.getProperty();
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see com.pn.px.commons.messages.AbstractMessage#getDescribe()
	 */
	@Override
	@JSONField(serialize = false)
	public String getDescribe() {
		// TODO Auto-generated method stub
		return super.getDescribe();
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see com.pn.px.commons.messages.AbstractMessage#getStatus()
	 */
	@Override
	@JSONField(serialize = false)
	public String getStatus() {
		// TODO Auto-generated method stub
		return super.getStatus();
	}

	/**
	 * @return the version
	 */
	public String getVersion() {
		return version;
	}

	/**
	 * @param version
	 *            the version to set
	 */
	public void setVersion(String version) {
		this.version = version;
	}


	/**
	 * @param pid
	 *            the pid to set
	 */
	public void setPid(String pid) {
		this.pid = pid;
	}

	/**
	 * @return the pid
	 */
	public String getPid() {
		return pid;
	}

	/**
	 * @return the digiSign
	 */
	public String getDigiSign() {
		return digiSign;
	}

	/**
	 * @param digiSign
	 *            the digiSign to set
	 */
	public void setDigiSign(String digiSign) {
		this.digiSign = digiSign;
	}

	/**
	 * @return the appRefer
	 */
	public String getAppRefer() {
		return appRefer;
	}

	/**
	 * @param appRefer
	 *            the appRefer to set
	 */
	public void setAppRefer(String appRefer) {
		this.appRefer = appRefer;
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
	/* (non-Javadoc)
	 * @see java.lang.Object#toString()
	 */
	@Override
	public String toString() {
		return "AppTextMessageRequest [version=" + version  +", appRefer=" + appRefer + ", digiSign=" + digiSign + ", pid=" + pid + ", token=" + token
				+ ", seqId=" + getSeqId() + ", encryptType=" + getEncryptType() + ", mac=" + getMac() + ", timestamp="
				+ getTimestamp() + ", data=" + getData()   +",deviceId=" + getDeviceId() + ", channelID=" + getChannelID() + ",locale=" + getLocale() +"]";
	}

	
}
