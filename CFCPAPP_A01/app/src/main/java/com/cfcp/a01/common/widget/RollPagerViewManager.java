package com.cfcp.a01.common.widget;

import android.content.Context;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.bumptech.glide.Glide;
import com.cfcp.a01.R;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.data.BannerResult;
import com.jude.rollviewpager.OnItemClickListener;
import com.jude.rollviewpager.RollPagerView;
import com.jude.rollviewpager.adapter.StaticPagerAdapter;
import com.jude.rollviewpager.hintview.IconHintView;

import java.util.List;

import rx.Observable;
import rx.Subscription;


/**
 * Created by Daniel on 2018/7/12.
 */

public class RollPagerViewManager {

    public interface ImageGetter
    {
        Observable<String[]> getImages();
    }
    //private String[] titileActivity = new String[]{"我是第1个","我是第2个","我是第3个","我是第4个","我是第5个"};
    private RollPagerView mLoopViewPager;
    //private UrlImageLoopAdapter mLoopAdapter;
    private Context context;
    private ImageGetter imageGetter;
    private Subscription subscription;

    private List<BannerResult.DataBean> activityList;
    public RollPagerViewManager(RollPagerView rollPagerView, List<BannerResult.DataBean> activityList)
    {
        this.mLoopViewPager = rollPagerView;
        this.context = rollPagerView.getContext();
        this.activityList = activityList;
    }

    public RollPagerViewManager(RollPagerView rollPagerView, ImageGetter imageGetter)
    {
        if(null == imageGetter)
        {
            throw  new NullPointerException("you must not have null image getter in rollpageview manager");
        }
        this.mLoopViewPager = rollPagerView;
        this.context = rollPagerView.getContext();
        this.imageGetter = imageGetter;
    }

    /*public void readyGo()
    {
        mLoopViewPager.setAdapter(mLoopAdapter = new UrlImageLoopAdapter(mLoopViewPager));
        mLoopViewPager.setPlayDelay(2000);
        mLoopViewPager.setHintView(new IconHintView(context, R.drawable.point_focus, R.drawable.point_normal));
        mLoopViewPager.setOnItemClickListener(new OnItemClickListener() {
            @Override
            public void onItemClick(int position) {
                Toast.makeText(context,"你点击了第 "+position+" 张图片",Toast.LENGTH_SHORT).show();
            }
        });

        subscription = imageGetter.getImages().observeOn(Schedulers.io())
                .subscribeOn(AndroidSchedulers.mainThread())
                .subscribe(new Action1<String[]>() {

                    @Override
                    public void call(String[] imgs) {
                        GameLog.log("加载到图片：" + Arrays.toString(imgs));
                        mLoopAdapter.setImgs(imgs);
                    }
                }, new Action1<Throwable>() {

                    @Override
                    public void call(Throwable throwable) {
                        //加载图片url失败
                        GameLog.log("加载图片失败");
                    }
                }, new Action0() {

                    @Override
                    public void call() {
                        GameLog.log("不管怎么样，加载图片过程结束");
                    }
                });

    }*/

    private void addCenterDots(int sizeDots,int currentPage,LinearLayout linearLayoutMainDots){
        ImageView[] dots  = new ImageView[sizeDots];
        linearLayoutMainDots.removeAllViews();
        for(int i=0;i<sizeDots;++i){
            dots[i] = (ImageView)LayoutInflater.from(context).inflate(R.layout.item_dot,null);
            //(ImageView) findViewById(R.id.iv_main_dots);//new ImageView(context);
            dots[i].setBackground(context.getResources().getDrawable(R.drawable.dot_normal));
            //dots[i].setBackgroundColor(context.getResources().getColor(R.color.text_color));
            //dots[i].setLayoutParams(new LinearLayout.LayoutParams(10,10));
            //dots[i].setPadding(20,20,0,0);
            linearLayoutMainDots.addView(dots[i]);
        }
        if(dots.length>0){
         //   dots[currentPage].setBackgroundColor(context.getResources().getColor(R.color.item_highlight_color));
          dots[currentPage].setBackground(context.getResources().getDrawable(R.drawable.dot_selected));
        }
        /*TextView[] dots  = new TextView[sizeDots];
        linearLayoutMainDots.removeAllViews();
        for(int i=0;i<sizeDots;++i){
            dots[i] = new TextView(context);
            dots[i].setText(Html.fromHtml("&#8226;"));
            dots[i].setTextSize(48);
            if(Build.VERSION.SDK_INT>=Build.VERSION_CODES.M){
                dots[i].setTextColor(Color.LTGRAY);
            }else{
                dots[i].setTextColor(Color.LTGRAY);
            }
            linearLayoutMainDots.addView(dots[i]);
        }
        if(dots.length>0){
            if(Build.VERSION.SDK_INT>=Build.VERSION_CODES.M){
                dots[currentPage].setTextColor(Color.RED);
            }else{
                dots[currentPage].setTextColor(Color.RED);
            }
        }*/
    }

    public void testImagesNet(Fragment fragment,final TextView textView, final LinearLayout linearLayoutMainDots)
    {
        //mLoopViewPager.setAdapter(new UrlImageLoopAdapter(mLoopViewPager));
        //addCenterDots(activityList.size(),0,linearLayoutMainDots);
        mLoopViewPager.setAdapter(new UrlImageAdapter(fragment));
        mLoopViewPager.setPlayDelay(5000);
        mLoopViewPager.setHintView(new IconHintView(context,R.drawable.point_focus, R.drawable.point_normal));
       // textView.setText(activityList.get(0).getTitle());
        mLoopViewPager.setOnPageChangeListener(new RollPagerView.PageChangeListener() {
            @Override
            public void page(int postion) {
                GameLog.log("banner的当前位置："+postion);
                if(postion>10){
                    return;
                }
               // addCenterDots(activityList.size(),postion,linearLayoutMainDots);
               // textView.setText(activityList.get(postion).getTitle());
                //Toast.makeText(context,"滑动了"+postion,Toast.LENGTH_SHORT).show();
            }
        });
        mLoopViewPager.setOnItemClickListener(new OnItemClickListener() {
            @Override
            public void onItemClick(int position) {
                /*if(activityList.get(position).getName().equals("promo")){
                    String userMoney = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_MONEY);
                    EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(userMoney, Client.baseUrl()+"template/promo.php?tip=app"+ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_BANNER))));
                }*/
                /*if(!Check.isEmpty(activityList.get(position).getDetailUrl()))
                EventBus.getDefault().post(new StartBrotherEvent(IntroduceFragment.newInstance(activityList.get(position).getTitle(),activityList.get(position).getDetailUrl())));
*/
                //Toast.makeText(context,"你点击了第 "+position+" 张图片",Toast.LENGTH_SHORT).show();
            }
        });
    }

    public void testImagesLocal(final TextView textView)
    {
        mLoopViewPager.setAdapter(new LocalImagePagerAdapter());
        mLoopViewPager.setPlayDelay(2000);
        mLoopViewPager.setHintView(new IconHintView(context,R.drawable.point_focus, R.drawable.point_normal));
        mLoopViewPager.setOnPageChangeListener(new RollPagerView.PageChangeListener() {
            @Override
            public void page(int postion) {
                GameLog.log("banner的当前位置："+postion);
                //textView.setText(activityList.get(postion).getTitle());
                //Toast.makeText(context,"滑动了"+postion,Toast.LENGTH_SHORT).show();
            }
        });
        mLoopViewPager.setOnItemClickListener(new OnItemClickListener() {
            @Override
            public void onItemClick(int position) {
                Toast.makeText(context,"你点击了第 "+position+" 张图片",Toast.LENGTH_SHORT).show();
            }
        });
    }
    
    public void destroy()
    {
        if(null != subscription && !subscription.isUnsubscribed())
        {
            subscription.unsubscribe();
        }
    }

    /*private class UrlImageLoopAdapter extends LoopPagerAdapter {
        String[] imgs = new String[0];

        public void setImgs(String[] imgs){
            this.imgs = imgs;
            notifyDataSetChanged();
        }


        public UrlImageLoopAdapter(RollPagerView viewPager) {
            super(viewPager);
        }

        @Override
        public View getView(ViewGroup container, int position) {
            GameLog.log("getView:"+imgs[position]);

            ImageView view = new ImageView(container.getContext());
          *//*  view.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                   GameLog.log("onClick");
                }
            });*//*
            view.setScaleType(ImageView.ScaleType.CENTER_CROP);
            view.setLayoutParams(new ViewGroup.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT));
            Picasso.with(context)
                    .load(imgs[position])
                    .placeholder(R.drawable.loading)
                    .into(view);
            return view;
        }

        @Override
        public int getRealCount() {
            //return imgs.length;
            return activityList.size();
        }

    }*/


    private class UrlImageAdapter extends StaticPagerAdapter {
        Fragment fragment;

        public UrlImageAdapter(Fragment fragment){
            this.fragment = fragment;
        }
        @Override
        public View getView(ViewGroup container, int position) {
            GameLog.log("首页Banner的地址是 "+activityList.get(position).getPath());
            LinearLayout linearLayout = new LinearLayout(container.getContext());
            ImageView view = new ImageView(container.getContext());
            //RoundCornerImageView view = new RoundCornerImageView(container.getContext());
            view.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                   GameLog.log("onClick");
                }
            });
            //view.onCornerAll(view);
            view.setScaleType(ImageView.ScaleType.CENTER_CROP);
            view.setLayoutParams(new ViewGroup.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT));
            Glide.with(fragment).load(activityList.get(position).getPath()).into(view);
            linearLayout.addView(view);
            return linearLayout;
        }

        @Override
        public int getCount() {
            return activityList.size();
        }

    }



    private class LocalImagePagerAdapter extends StaticPagerAdapter {
        /*int[] imgs = new int[]{
                R.drawable.img1,
                R.drawable.img2,
                R.drawable.img3,
                R.drawable.img4,
                R.drawable.img5,
        };*/
        int[] imgs = new int[]{
                R.mipmap.home_icon,
                R.mipmap.home_icon,
                R.mipmap.home_icon,
        };

        @Override
        public View getView(ViewGroup container, int position) {
            ImageView view = new ImageView(container.getContext());
            view.setScaleType(ImageView.ScaleType.CENTER_CROP);
            view.setLayoutParams(new ViewGroup.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT));
            view.setImageResource(imgs[position]);
            return view;
        }


        @Override
        public int getCount() {
            return imgs.length;
        }
    }
}