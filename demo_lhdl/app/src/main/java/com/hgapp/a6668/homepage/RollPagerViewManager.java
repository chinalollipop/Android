package com.hgapp.a6668.homepage;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.hgapp.a6668.R;
import com.hgapp.a6668.common.http.Client;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.widgets.RoundCornerImageView;
import com.hgapp.a6668.data.BannerResult;
import com.hgapp.a6668.homepage.online.OnlineFragment;
import com.hgapp.common.util.GameLog;
import com.jude.rollviewpager.OnItemClickListener;
import com.jude.rollviewpager.RollPagerView;
import com.jude.rollviewpager.RollPagerView.PageChangeListener;
import com.jude.rollviewpager.adapter.StaticPagerAdapter;
import com.jude.rollviewpager.hintview.IconHintView;
import com.squareup.picasso.Picasso;

import org.greenrobot.eventbus.EventBus;

import java.util.List;

import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;
import rx.Observable;
import rx.Subscription;

import static com.hgapp.common.util.Utils.getContext;


/**
 * Created by Daniel on 2018/7/12.
 */

public class RollPagerViewManager {

    public interface ImageGetter
    {
        public Observable<String[]> getImages();
    }
    //private String[] titileActivity = new String[]{"我是第1个","我是第2个","我是第3个","我是第4个","我是第5个"};
    private RollPagerView mLoopViewPager;
    //private UrlImageLoopAdapter mLoopAdapter;
    private Context context;
    private ImageGetter imageGetter;
    private Subscription subscription;

    private List<BannerResult.DataBean>  activityList;
    public RollPagerViewManager(RollPagerView rollPagerView,List<BannerResult.DataBean> activityList)
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

    public void testImagesNet(final TextView textView, final LinearLayout linearLayoutMainDots)
    {
        //mLoopViewPager.setAdapter(new UrlImageLoopAdapter(mLoopViewPager));
        //addCenterDots(activityList.size(),0,linearLayoutMainDots);
        mLoopViewPager.setAdapter(new UrlImageAdapter());
        mLoopViewPager.setPlayDelay(5000);
//        mLoopViewPager.setHintView(new IconHintView(context,R.drawable.point_focus, R.drawable.point_normal));
       // textView.setText(activityList.get(0).getTitle());
        mLoopViewPager.setOnPageChangeListener(new PageChangeListener() {
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
                if(activityList.get(position).getName().equals("promo")){
                    String userMoney = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_MONEY);
                    EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(userMoney, Client.baseUrl()+"template/promo.php?tip=app"+ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_BANNER))));
                }
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
        mLoopViewPager.setOnPageChangeListener(new PageChangeListener() {
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

        @Override
        public View getView(ViewGroup container, int position) {
            GameLog.log("getView:"+activityList.get(position).getImg_path());
//            ImageView view = new ImageView(container.getContext());
            RoundCornerImageView view = new RoundCornerImageView(container.getContext());
            view.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                   GameLog.log("onClick");
                }
            });
            //view.onCornerAll(view);
            view.setScaleType(ImageView.ScaleType.CENTER_CROP);
            view.setLayoutParams(new ViewGroup.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT));
            Picasso.with(context)
                    .load(activityList.get(position).getImg_path())
                    .placeholder(R.drawable.loading)
                    .into(view);
            return view;
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
                R.mipmap.focus1,
                R.mipmap.focus2,
                R.mipmap.focus3,
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
