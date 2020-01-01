package com.nhg.xhg.common.http;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.serializer.SerializerFeature;
import com.nhg.xhg.common.http.request.AppTextMessageRequest;
import com.nhg.xhg.common.http.util.MacUtil;
import com.nhg.xhg.data.RestartLoginResult;
import com.nhg.xhg.login.fastlogin.LoginFragment;
import com.nhg.common.util.Check;
import com.nhg.common.util.GameLog;

import org.greenrobot.eventbus.EventBus;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.nio.charset.Charset;
import java.nio.charset.UnsupportedCharsetException;

import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;
import okhttp3.Interceptor;
import okhttp3.MediaType;
import okhttp3.Request;
import okhttp3.RequestBody;
import okhttp3.Response;
import okhttp3.ResponseBody;
import okio.Buffer;
import okio.BufferedSource;

/**
 * Created by Daniel on 2017/5/12.
 * 当某个请求-响应的响应码为401即要求重新认证的时候，此类的方法被调用，以取得新的token，放入request中，重新请求。
 */

public class TokenInterceptor implements Interceptor {
    //private IUserManager userManager = UserManagerFactory.get();
    private static final Charset UTF8 = Charset.forName("UTF-8");
    private ClientConfig config;
    public TokenInterceptor(ClientConfig config)
    {
        this.config = config;
    }

    @Override
    public Response intercept(Chain chain)throws IOException{
        Request request = chain.request();
        RequestBody requestBody = request.body();
        Response response = chain.proceed(request);
        GameLog.log("code:"+response.code()+" message "+response.message());
        //开始重试 有且仅重试一次
        if(response.code()==401 && null == request.header("DoneTryToken"))
        {
            /*RefreshTokenContract.Presenter presenter = Injections.inject((RefreshTokenContract.View)null);
            String newToken = presenter.synRefreshToken();
            if(Check.isEmpty(newToken))
            {
                return response;
            }
            userManager.modifyToken(newToken);*/

            Request completeRequest = request.newBuilder()
                    .post(withNewToken(request.body(),""))
                    .addHeader("DoneTryToken","yes")
                    .build();
            return chain.proceed(completeRequest);
        }else{
            ResponseBody responseBody = response.body();
            long contentLength = responseBody.contentLength();
            String bodySize = contentLength != -1L?contentLength + "-byte":"unknown-length";
            BufferedSource var33 = responseBody.source();
            var33.request(9223372036854775807L);
            Buffer var34 = var33.buffer();
            Charset charset = UTF8;
            MediaType contentType = responseBody.contentType();
            if(contentType != null) {
                try {
                    charset = contentType.charset(UTF8);
                } catch (UnsupportedCharsetException var26) {
                    GameLog.log("");
                    GameLog.log("Couldn\'t decode the response body; charset is likely malformed.");
                    GameLog.log("<-- END HTTP");
                }
            }

            if(contentLength != 0L) {
                //GameLog.log("返回的数据是xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx：");
                String resposeData = var34.clone().readString(charset);
                if(Check.isEmpty(resposeData)){
                    return response;
                }
                //GameLog.log(resposeData);
                try{
                    RestartLoginResult restartLoginResult =  JSON.parseObject(resposeData, RestartLoginResult.class);
                    if(restartLoginResult.getStatus().equals("401.1")){
                        LoginFragment.newInstance().showMessage(restartLoginResult.getDescribe());
                        //LoginFragment.newInstance().getPreFragment().popTo(LoginFragment.class,true);
                        GameLog.log("返回的异常信息是："+restartLoginResult.getDescribe());
                        Client.cancelAllRequest();
                        EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    }
                }catch (Exception e){
                    GameLog.log("返回的异常信息是："+e.getMessage());
                }
            }

            GameLog.log("<-- END HTTP (" + var34.size() + "-byte body)");
        }
        return response;
    }



    private RequestBody withNewToken(RequestBody requestBody,String newToken)
    {
        SerializerFeature[] serializerFeature = {
                SerializerFeature.WriteMapNullValue,
                SerializerFeature.WriteNullListAsEmpty,
                SerializerFeature.WriteNullStringAsEmpty,
                SerializerFeature.WriteNullNumberAsZero
        };

        String body = fromRequestBody(requestBody);
        if(Check.isEmpty(body))
        {
            return null;
        }
        AppTextMessageRequest messageRequest = JSON.parseObject(body, AppTextMessageRequest.class);
        messageRequest.setToken(newToken);
        messageRequest.setMac(MacUtil.generateMac(messageRequest));
        String newjsonstring = JSON.toJSONString(messageRequest,serializerFeature);
        MediaType contentType = MediaType.parse("application/json");
        return RequestBody.create(contentType,newjsonstring);
    }

    private String fromRequestBody(RequestBody requestBody)
    {
        ByteArrayOutputStream outputStream = null;
        Buffer buffer=null;
        try {
            buffer = new Buffer();
            requestBody.writeTo(buffer);
            outputStream = new ByteArrayOutputStream(4096);
            buffer.copyTo(outputStream);
            String body = outputStream.toString("UTF-8");
            return body;
        } catch (IOException e) {
            e.printStackTrace();
        }
        finally {
            if(null != buffer)
            {
                buffer.close();
            }
            if(null != outputStream)
            {
                try {
                    outputStream.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }
        return "";
    }
}
