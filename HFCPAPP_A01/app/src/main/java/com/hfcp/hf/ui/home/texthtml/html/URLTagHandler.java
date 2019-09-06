package com.hfcp.hf.ui.home.texthtml.html;

import android.content.Context;
import android.content.res.ColorStateList;
import android.content.res.Resources;
import android.graphics.Bitmap;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.graphics.drawable.Drawable;
import android.text.Editable;
import android.text.Html.TagHandler;
import android.text.Spannable;
import android.text.Spanned;
import android.text.TextUtils;
import android.text.style.AbsoluteSizeSpan;
import android.text.style.ClickableSpan;
import android.text.style.ForegroundColorSpan;
import android.text.style.ImageSpan;
import android.text.style.TextAppearanceSpan;
import android.util.TypedValue;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.PopupWindow;

import com.hfcp.hf.R;
import com.hfcp.hf.common.utils.GameLog;
import com.hfcp.hf.ui.home.texthtml.imageload.ImageLoad;
import com.squareup.picasso.Picasso;
import com.squareup.picasso.Target;

import org.xml.sax.XMLReader;

import java.lang.reflect.Field;
import java.util.HashMap;
import java.util.Locale;
import java.util.Map;
/**
 * Created by Daniel on 2019/3/2.
 */
public class URLTagHandler implements TagHandler {

    private int startIndex = 0;
    private int stopIndex = 0;
    final HashMap<String, String> attributes = new HashMap<String, String>();
    private ColorStateList mOriginColors;
    private Context mContext;
    private PopupWindow popupWindow;
    //需要放大的图片
    private ZoomImageView tecent_chat_image;
    private ImageView image_scale_cancel;

    public URLTagHandler(Context context,ColorStateList colorStateList) {
        mContext = context.getApplicationContext();
        mOriginColors = colorStateList;
        View popView = LayoutInflater.from(context).inflate(R.layout.pub_zoom_popwindow_layout, null);
        tecent_chat_image = (ZoomImageView) popView.findViewById(R.id.image_scale_image);
        image_scale_cancel = (ImageView) popView.findViewById(R.id.image_scale_cancel);

        popView.findViewById(R.id.image_scale_cancel).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (popupWindow != null && popupWindow.isShowing()) {
                    popupWindow.dismiss();
                }
            }
        });
        popupWindow = new PopupWindow(popView, ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT);
        popupWindow.setFocusable(true);
        popupWindow.setOutsideTouchable(true);// 设置允许在外点击消失
        ColorDrawable dw = new ColorDrawable(0x50000000);
        popupWindow.setBackgroundDrawable(dw);
    }

    public void startSpan(String tag, Editable output, XMLReader xmlReader) {
        startIndex = output.length();
    }

    public void endSpan(String tag, Editable output, XMLReader xmlReader) throws NullPointerException{
        stopIndex = output.length();

        String color = attributes.get("color");
        String size = attributes.get("size");
        String style = attributes.get("style");
        if (!TextUtils.isEmpty(style)){
            analysisStyle(startIndex,stopIndex,output,style);
        }
        if (!TextUtils.isEmpty(size)) {
            size = size.split("px")[0];
        }
        if(!TextUtils.isEmpty(color)){
            if (color.startsWith("@")) {
                Resources res = Resources.getSystem();
                String name = color.substring(1);
                int colorRes = res.getIdentifier(name, "color", "android");
                if (colorRes != 0) {
                    output.setSpan(new ForegroundColorSpan(colorRes), startIndex, stopIndex,  Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
                }
            }if (((String)color).contains("rgb"))
            {
                String []attrArray = ((String)color).replaceAll("rgb", "").replaceAll("\\(", "").replaceAll("\\)", "").split(", ");
                if (attrArray.length == 3)
                {
                    output.setSpan(new ForegroundColorSpan(Color.rgb(Integer.valueOf(Integer.parseInt(attrArray[0])).intValue(), Integer.valueOf(Integer.parseInt(attrArray[1])).intValue(), Integer.valueOf(Integer.parseInt(attrArray[2])).intValue())), startIndex, stopIndex, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
                }
            }
            else
            {
                try
                {
                    output.setSpan(new ForegroundColorSpan(Color.parseColor((String)color)), startIndex, stopIndex, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
                }
                catch (Exception localException)
                {
                    reductionFontColor(startIndex, stopIndex, output);
                    GameLog.log("localException："+localException.toString());
                }
            }
        }
        if (!TextUtils.isEmpty(size)) {
            int fontSizePx = 14;
            if (null != mContext){
                fontSizePx = sp2px(mContext,Integer.parseInt(size));
            }
            output.setSpan(new AbsoluteSizeSpan(fontSizePx), startIndex, stopIndex, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
        }

    }


    private void processAttributes(final XMLReader xmlReader) {
        try {
            Field elementField = xmlReader.getClass().getDeclaredField("theNewElement");
            elementField.setAccessible(true);
            Object element = elementField.get(xmlReader);
            Field attsField = element.getClass().getDeclaredField("theAtts");
            attsField.setAccessible(true);
            Object atts = attsField.get(element);
            Field dataField = atts.getClass().getDeclaredField("data");
            dataField.setAccessible(true);
            String[] data = (String[])dataField.get(atts);
            Field lengthField = atts.getClass().getDeclaredField("length");
            lengthField.setAccessible(true);
            int len = (Integer)lengthField.get(atts);

            /**
             * MSH: Look for supported attributes and add to hash map.
             * This is as tight as things can get :)
             * The data index is "just" where the keys and values are stored.
             */
            for(int i = 0; i < len; i++)
                attributes.put(data[i * 5 + 1], data[i * 5 + 4]);
        }
        catch (Exception e) {
        }
    }

    /**
     * 还原为原来的颜色
     * @param startIndex
     * @param stopIndex
     * @param editable
     */
    private void reductionFontColor(int startIndex,int stopIndex,Editable editable){
        if (null != mOriginColors){
            editable.setSpan(new TextAppearanceSpan(null, 0, 0, mOriginColors, null),
                    startIndex, stopIndex,
                    Spannable.SPAN_EXCLUSIVE_EXCLUSIVE);
        }else {
            editable.setSpan(new ForegroundColorSpan(0xff2b2b2b), startIndex, stopIndex, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
        }
    }

    /**
     * 解析style属性
     * @param startIndex
     * @param stopIndex
     * @param editable
     * @param style
     */
    private void analysisStyle(int startIndex,int stopIndex,Editable editable,String style){
        GameLog.log("style："+style);//<span style=\"color: rgb(255, 0, 0);\"><span style=\"color: rgb(255, 0, 0);\">
        String[] attrArray = style.split(";");
        Map<String,String> attrMap = new HashMap<>();
        if (null != attrArray){
            for (String attr:attrArray){
                String[] keyValueArray = attr.split(":");
                if (null != keyValueArray && keyValueArray.length == 2){
                    // 记住要去除前后空格
                    attrMap.put(keyValueArray[0].trim(),keyValueArray[1].trim());
                }
            }
        }
        GameLog.log("attrMap："+attrMap.toString());

        String color = attrMap.get("color");
        String fontSize = attrMap.get("font-size");
        if (!TextUtils.isEmpty(fontSize)) {
            fontSize = fontSize.split("px")[0];
        }
        if(!TextUtils.isEmpty(color)){
            if (((String)color).contains("rgb"))
            {
                attrArray = ((String)color).replaceAll("rgb", "").replaceAll("\\(", "").replaceAll("\\)", "").split(", ");
                if (attrArray.length == 3)
                {
                    editable.setSpan(new ForegroundColorSpan(Color.rgb(Integer.valueOf(Integer.parseInt(attrArray[0])).intValue(), Integer.valueOf(Integer.parseInt(attrArray[1])).intValue(), Integer.valueOf(Integer.parseInt(attrArray[2])).intValue())), startIndex, stopIndex, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
                }
            }
            else if (color.startsWith("@")) {
                Resources res = Resources.getSystem();
                String name = color.substring(1);
                int colorRes = res.getIdentifier(name, "color", "android");
                if (colorRes != 0) {
                    editable.setSpan(new ForegroundColorSpan(colorRes), startIndex, stopIndex,  Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
                }
            } else {
                try {
                    editable.setSpan(new ForegroundColorSpan(Color.parseColor(color)), startIndex, stopIndex,  Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
                } catch (Exception e) {
                    GameLog.log("localException："+e.toString());
                    reductionFontColor(startIndex,stopIndex,editable);
                }
            }
        }

        if (!TextUtils.isEmpty(fontSize)) {
            int fontSizePx = 14;
            if (null != mContext){
                fontSizePx = sp2px(mContext,Integer.parseInt(fontSize));
            }
            editable.setSpan(new AbsoluteSizeSpan(fontSizePx), startIndex, stopIndex, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
        }


    }



    @Override
    public void handleTag(boolean opening, String tag, Editable output, XMLReader xmlReader) {
        processAttributes(xmlReader);
        // 处理标签<img>
        if (tag.toLowerCase(Locale.getDefault()).equals("img")) {
            // 获取长度
            int len = output.length();
            // 获取图片地址
            ImageSpan[] images = output.getSpans(len - 1, len, ImageSpan.class);
            String imgURL = images[0].getSource();
            // 使图片可点击并监听点击事件
            output.setSpan(new ClickableImage(mContext, imgURL), len - 1, len, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
        }else if(tag.toLowerCase(Locale.getDefault()).equals("daniel_style")){//处理<span style=\"color: rgb(255, 0, 0);\">刮刮乐，</span>
            if(opening){
                startSpan(tag, output, xmlReader);
            }else{
                endSpan(tag, output, xmlReader);
                attributes.clear();
            }
        }
    }

    private class ClickableImage extends ClickableSpan {
        private String url;
        private Context context;

        public ClickableImage(Context context, String url) {
            this.context = context;
            this.url = url;
        }

        @Override
        public void onClick(View widget) {
            // 进行图片点击之后的处理
            popupWindow.showAtLocation(widget, Gravity.BOTTOM | Gravity.CENTER_HORIZONTAL, 0, 0);
            final Target target = new Target() {
                @Override
                public void onBitmapLoaded(Bitmap bitmap, Picasso.LoadedFrom from) {
                    tecent_chat_image.setImageBitmap(bitmap);
                }

                @Override
                public void onBitmapFailed(Drawable errorDrawable) {
                    tecent_chat_image.setImageDrawable(errorDrawable);
                }

                @Override
                public void onPrepareLoad(Drawable placeHolderDrawable) {
                    tecent_chat_image.setImageDrawable(placeHolderDrawable);
                }
            };
            tecent_chat_image.setTag(target);
            ImageLoad.loadPlaceholder(context, url, target);
        }
    }


    /**
     * 缩放独立像素 转换成 像素
     * @param context
     * @param spValue
     * @return
     */
    public static int sp2px(Context context, float spValue) {
        return (int) (TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_SP, spValue, context.getResources().getDisplayMetrics()) + 0.5f);

    }
}
/*
* git init
git add README.md
git commit -m "first commit"
git remote add origin git@github.com:evernightking/textview-load-html.git
git push -u origin master
*
* */