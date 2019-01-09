package com.cfcp.a01.http.util;


import com.cfcp.a01.http.ClientConfig;
import com.cfcp.a01.utils.GameLog;
import com.cfcp.a01.utils.Timber;

import java.io.IOException;
import java.util.HashMap;
import java.util.Map;

import okhttp3.FormBody;
import okhttp3.MediaType;
import okhttp3.Request;
import okhttp3.RequestBody;

/**
 * Created by Nereus on 2017/8/30.
 */

public class RequestBuilder {
    private ClientConfig clientConfig;
    private String token;
    public RequestBuilder(ClientConfig config,String token)
    {
        this.clientConfig = config;
        this.token = token;
    }

    public Request newRequest(Request originalRequest) throws IOException {
        Timber.tag(getClass().getName());
        GameLog.log("请求的地址："+originalRequest.url());
        GameLog.log("请求的方式："+originalRequest.method());
        if(!"POST".equals(originalRequest.method()))
        {
            Timber.e("请求必须是POST请求");
            //throw new IllegalArgumentException("请求方法必须是POST");
            return originalRequest.newBuilder().get().build();
        }
        //好啦，开始修改请求
        RequestBody requestBody = originalRequest.body();
        FormBody formBody = null;
        if(null != requestBody && (requestBody instanceof FormBody) &&  (requestBody.contentLength() != 0) )
        {
            formBody = (FormBody)requestBody;
        }
        MediaType contentType = MediaType.parse("application/x-www-form-urlencoded");//application/x-www-form-urlencoded  application/json

        RequestBody newRequestBody = RequestBody.create(contentType,convert(formBody));
        Request newRequest = originalRequest.newBuilder().post(newRequestBody).build();
        return newRequest;
    }

    /**
     * @return
     */
    private String convert(FormBody formBody)
    {
        Timber.d("打印请求参数");
        Map<String,Object> map = new HashMap<>();
        StringBuilder stringBuilder = new StringBuilder();
        if(null != formBody)
        {
            final int size = formBody.size();
            for(int index = 0;index < size;index++)
            {
                String name = formBody.name(index);
                String value = formBody.value(index);
                Timber.d("%s ---> %s",name,value);
                map.put(name,value);
                stringBuilder.append(name).append("=").append(value).append("&");
            }
        }

        //return getRequestBody(map);
        return getRequestBody(stringBuilder.toString());
    }

    private String  getRequestBody(String requestString)//Map<String,Object> data
    {
        /*SerializerFeature[] serializerFeature = {
                SerializerFeature.WriteMapNullValue,
                SerializerFeature.WriteNullListAsEmpty,
                SerializerFeature.WriteNullStringAsEmpty,
                SerializerFeature.WriteNullNumberAsZero
        };

        AppTextMessageRequest messageRequest = new AppTextMessageRequest();
        messageRequest.setChannelID(clientConfig.channelID);
        messageRequest.setAppRefer(clientConfig.appRefer);
        messageRequest.setDigiSign("1111-2222-1");
        messageRequest.setEncryptType(EncryptType.DES3.name());
        messageRequest.setPid(clientConfig.productOwner);
        messageRequest.setSeqId("a1000000001");
        messageRequest.setTimestamp(new Date().getTime());
        messageRequest.setLocale(clientConfig.locale);
        messageRequest.setDeviceId(clientConfig.deviceId);

        if(Check.isEmpty(token))
        {
            token = "0000";
            Timber.e("有错误，有毛病 token为空");
        }
        messageRequest.setToken(token);
        messageRequest.setVersion(clientConfig.version);
        Gson gson = new Gson();*/
        if(null != requestString && !requestString.isEmpty())
        {
            /*String jsonstr = gson.toJson(requestString);
            JsonReader reader = new JsonReader(new StringReader(jsonstr));
            reader.setLenient(true);*/
            //GameLog.log("-------jsonstr-------"+requestString);
            /*String encryptText = Des3Util.encrypt(jsonstr, "1a0dcc06af4585e83a1c4967");
            messageRequest.setData(encryptText);*/
            return  requestString.substring(0,requestString.length()-1);//gson.toJson(data).trim();
            //return JSON.toJSONString(reader);
        }
        return "";
        //messageRequest.setMac(MacUtil.generateMac(messageRequest));
        //Timber.d("request:%s",messageRequest);
       // return JSON.toJSONString(messageRequest, serializerFeature);

    }
}
