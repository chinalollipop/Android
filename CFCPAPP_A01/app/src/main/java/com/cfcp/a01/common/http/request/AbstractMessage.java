package com.cfcp.a01.common.http.request;


import java.io.Serializable;
import java.util.Collections;
import java.util.HashMap;
import java.util.Map;

public class AbstractMessage implements Message, Serializable {
    private static final long serialVersionUID = -7152338643144442290L;
    private String id;
    private String status;
    private String describe;
    private Map<String, Object> property = new HashMap();

    public AbstractMessage() {
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getId() {
        return this.id;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public String getStatus() {
        return this.status;
    }
    public int getiStatus()
    {
        try
        {
            return Integer.parseInt(status);
        }
        catch (NumberFormatException e)
        {

        }
        return 400;
    }

    public void setProperty(String key, Object value) {
        this.property.put(key, value);
    }

    public Object getProperty(String key) {
        return this.property.get(key);
    }

    public Map<String, Object> getProperty() {
        Map<String, Object> copyProperty = Collections.unmodifiableMap(this.property);
        return copyProperty;
    }

    @Override
    public String getDescribe() {
        return describe;
    }

    @Override
    public void setDescribe(String describe) {
        this.describe = describe;
    }
}

