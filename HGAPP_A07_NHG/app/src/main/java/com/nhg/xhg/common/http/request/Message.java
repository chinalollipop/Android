package com.nhg.xhg.common.http.request;


import java.util.Map;

public interface Message {
    void setId(String var1);

    String getId();

    void setStatus(String var1);

    String getStatus();

    void setDescribe(String var1);

    String getDescribe();

    void setProperty(String var1, Object var2);

    Object getProperty(String var1);

    Map<String, Object> getProperty();
}
