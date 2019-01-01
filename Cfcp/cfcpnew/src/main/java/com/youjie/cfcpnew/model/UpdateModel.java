package com.youjie.cfcpnew.model;

import java.io.Serializable;

/**
 * Created by Colin on 2017/12/24.
 * app检查更新实体类
 */
public class UpdateModel implements Serializable {
    public String id;
    public String app_type;
    public String version;
    public String version_code;
    public String is_force;
    public String apk_url;
    public String upgrade_point;
    public String status;
    public String create_time;
    public String title;
    public String update_time;
}
