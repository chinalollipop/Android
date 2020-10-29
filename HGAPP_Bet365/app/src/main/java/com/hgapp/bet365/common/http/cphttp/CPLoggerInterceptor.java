package com.hgapp.bet365.common.http.cphttp;


import com.hgapp.common.util.GameLog;

import java.io.IOException;
import java.nio.charset.Charset;
import java.nio.charset.UnsupportedCharsetException;
import java.util.concurrent.TimeUnit;

import okhttp3.Connection;
import okhttp3.Headers;
import okhttp3.Interceptor;
import okhttp3.MediaType;
import okhttp3.Protocol;
import okhttp3.Request;
import okhttp3.RequestBody;
import okhttp3.Response;
import okhttp3.ResponseBody;
import okio.Buffer;
import okio.BufferedSource;

/**
 * Created by Nereus on 2017/4/17.
 */

public class CPLoggerInterceptor implements Interceptor {
    private static final Charset UTF8 = Charset.forName("UTF-8");

    public CPLoggerInterceptor() {
       
    }
    

    public Response intercept(Chain chain) throws IOException {
        Request request = chain.request().newBuilder().addHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8").build();

        
            boolean logBody = true;
            boolean logHeaders = true;
            RequestBody requestBody = request.body();
            boolean hasRequestBody = requestBody != null;
            Connection connection = chain.connection();
            Protocol protocol = connection != null? Protocol.HTTP_1_0: Protocol.HTTP_1_1;
            String requestStartMessage = "--> " + request.method() + ' ' + request.url() + ' ' + protocol;
            if(!logHeaders && hasRequestBody) {
                requestStartMessage = requestStartMessage + " (" + requestBody.contentLength() + "-byte body)";
            }

            GameLog.log(requestStartMessage);
            if(logHeaders) {
                if(hasRequestBody) {
                    if(requestBody.contentType() != null) {
                        GameLog.log("Content-Type: " + requestBody.contentType());
                    }

                    if(requestBody.contentLength() != -1L) {
                        GameLog.log("Content-Length: " + requestBody.contentLength());
                    }
                }

                Headers startNs = request.headers();
                int buffer = 0;

                for(int response = startNs.size(); buffer < response; ++buffer) {
                    String tookMs = startNs.name(buffer);
                    if(!"Content-Type".equalsIgnoreCase(tookMs) && !"Content-Length".equalsIgnoreCase(tookMs)) {
                        GameLog.log(tookMs + ": " + startNs.value(buffer));
                    }
                }

                if(logBody && hasRequestBody) {
                    if(this.bodyEncoded(request.headers())) {
                        GameLog.log("--> END " + request.method() + " (encoded body omitted)");
                    } else {
                        Buffer var28 = new Buffer();
                        requestBody.writeTo(var28);
                        Charset var29 = UTF8;
                        MediaType var31 = requestBody.contentType();
                        if(var31 != null) {
                            var29 = var31.charset(UTF8);
                        }

                        GameLog.log("-------jsonstr-------");
                        GameLog.log(var28.readString(var29));
                        GameLog.log("--> END " + request.method() + " (" + requestBody.contentLength() + "-byte body)");
                    }
                } else {
                    GameLog.log("--> END " + request.method());
                }
            }

            long var27 = System.nanoTime();
            Response var30 = chain.proceed(request);
            long var32 = TimeUnit.NANOSECONDS.toMillis(System.nanoTime() - var27);
            ResponseBody responseBody = var30.body();
            long contentLength = responseBody.contentLength();
            String bodySize = contentLength != -1L?contentLength + "-byte":"unknown-length";
            GameLog.log("<-- " + var30.code() + ' ' + var30.message() + ' ' + var30.request().url() + " (" + var32 + "ms" + (!logHeaders?", " + bodySize + " body":"") + ')');
            if(logHeaders) {
                Headers headers = var30.headers();
                int source = 0;

                for(int buffer1 = headers.size(); source < buffer1; ++source) {
                    GameLog.log(headers.name(source) + ": " + headers.value(source));
                }

                //if(logBody && HttpEngine.hasBody(var30)) {
                if(logBody ) {
                    if(this.bodyEncoded(var30.headers())) {
                        GameLog.log("<-- END HTTP (encoded body omitted)");
                    } else {
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
                                return var30;
                            }
                        }

                        if(contentLength != 0L) {
                            GameLog.log("");
                            GameLog.log(var34.clone().readString(charset));
                        }

                        GameLog.log("<-- END HTTP (" + var34.size() + "-byte body)");
                    }
                } else {
                    GameLog.log("<-- END HTTP");
                }
            }

            return var30;
        
    }

    private boolean bodyEncoded(Headers headers) {
        String contentEncoding = headers.get("Content-Encoding");
        return contentEncoding != null && !contentEncoding.equalsIgnoreCase("identity");
    }
}
