package com.hgapp.bet365.common.comment;


import com.hgapp.common.util.GameLog;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.RandomAccessFile;
import java.nio.ByteBuffer;
import java.nio.ByteOrder;
import java.util.zip.ZipFile;

/**
 * Created by ak on 2018/4/21.
 */

public class CommentUtils {
    public static byte[] short2Byte(short number) {
        int temp = number;
        byte[] b = new byte[2];
        for (int i = 0; i < b.length; i++) {
            b[i] = new Integer(temp & 0xff).byteValue();//将最低位保存在最低位
            temp = temp >> 8; // 向右移8位
        }
        return b;
    }


    public static void writeAPK(File file, String comment) {
        // TODO Auto-generated method stub
        ZipFile zipFile = null;
        ByteArrayOutputStream outputStream = null;
        RandomAccessFile accessFile = null;
        try {
            zipFile = new ZipFile(file);
            String zipComment = zipFile.getComment();
            // 判断是否包含comment信息
            if (zipComment != null) {
                // 如果有 则返回
                return;
            }
            byte[] bytecomment = comment.getBytes();
            outputStream = new ByteArrayOutputStream();
            // 写入comment和长度`
            outputStream.write(bytecomment);
            outputStream.write(CommentUtils.short2Byte((short) bytecomment.length));
            byte[] commentdata = outputStream.toByteArray();

            accessFile = new RandomAccessFile(file, "rw");
            accessFile.seek(file.length() - 2); // comment长度是short类型
            accessFile.write(CommentUtils.short2Byte((short) commentdata.length)); // 重新写入comment长度，注意Android apk文件使用的是ByteOrder.LITTLE_ENDIAN（小端序）；
            accessFile.write(commentdata);
            accessFile.close();
            System.out.println("插入完成！");
            GameLog.log("插入完成！");
        } catch (Exception e) {
            // TODO: handle exception
            GameLog.log("错误消息："+e.toString());
        } finally {
            try {
                if (zipFile != null) {
                    zipFile.close();
                }
                if (outputStream != null) {
                    outputStream.close();
                }
                if (accessFile != null) {
                    accessFile.close();
                }
            } catch (Exception e) {

            }
        }
    }

    public static String readAPK(File file) {
        byte[] bytes = null;
        try {
            RandomAccessFile accessFile = new RandomAccessFile(file, "r");
            long index = accessFile.length();

            bytes = new byte[2];
            index = index - bytes.length;
            accessFile.seek(index);
            accessFile.readFully(bytes);

            int contentLength = stream2Short(bytes, 0);

            bytes = new byte[contentLength];//short2Stream((short)bytes.length)
            index = index - bytes.length;
            accessFile.seek(index);
            accessFile.readFully(bytes);
            String a = new String(bytes, "utf-8");
            GameLog.log("Hello:"+a);
            return a;
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
        return null;
    }

    private static short stream2Short(byte[] stream, int offset) {
        ByteBuffer buffer = ByteBuffer.allocate(2);
        buffer.order(ByteOrder.LITTLE_ENDIAN);
        buffer.put(stream[offset]);
        buffer.put(stream[offset + 1]);
        return buffer.getShort(0);
    }

    public static int short2Stream(short data) {
        ByteBuffer buffer = ByteBuffer.allocate(2);
        buffer.order(ByteOrder.LITTLE_ENDIAN);
        buffer.putShort(data);
        buffer.flip();
        return buffer.getInt();
    }
}
