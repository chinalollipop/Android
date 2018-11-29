package com.qpweb.a01.http;

import com.vector.update_app.UpdateAppBean;

public class UpdateAppBeanItem extends UpdateAppBean {
    private String id ;
    private String name ;

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }
}
