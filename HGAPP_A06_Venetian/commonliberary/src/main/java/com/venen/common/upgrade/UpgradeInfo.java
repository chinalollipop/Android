package com.venen.common.upgrade;

import com.google.gson.annotations.SerializedName;

import java.io.Serializable;

/**
 * Created by Nereus on 2017/4/17.
 */

public class UpgradeInfo implements Serializable{
    @SerializedName("version")
    public String version;
    @SerializedName("content")
    public String content;
}
