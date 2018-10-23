package com.hgapp.common.activities;

import android.content.res.Configuration;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.view.KeyEvent;
import android.view.MotionEvent;
import android.view.View;
import android.webkit.WebChromeClient;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ProgressBar;

import com.example.commonliberary.R;
import com.hgapp.common.util.GameLog;
import com.hgapp.common.util.ProgressManager;


public class WebviewActivity extends AppCompatActivity {

    private WebView webView;
    private ProgressBar progressBar;
    private ProgressManager progressManager;
    private View progressgroup;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_webview);
        initview();

        progressManager = new ProgressManager(progressBar,progressgroup);
        progressManager.setShowProgressOnce(true);
        if(null != webView.getBackground())
        {
            webView.getBackground().setAlpha(1);
        }
    }


    @Override
    public void onStart()
    {
        super.onStart();
    }

    @Override
    public void onResume()
    {
        super.onResume();
        webView.onResume();
    }

    @Override
    public void onPause()
    {
        super.onPause();
        webView.onPause();
    }
    @Override
    public void onStop()
    {
        super.onStop();
    }
    @Override
    public void onDestroy()
    {
        super.onDestroy();
        webView.destroy();
    }

    private void initview()
    {
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        toolbar.setVisibility(View.GONE);

        progressBar = (ProgressBar)findViewById(R.id.load_progress);
        FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.fab);
        fab.setVisibility(View.GONE);
        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Snackbar.make(view, "Replace with your own action", Snackbar.LENGTH_LONG)
                        .setAction("Action", null).show();
            }
        });

        webView = (WebView)findViewById(R.id.webview);
        webView.setFitsSystemWindows(true);

        webviewsetting(webView);
        webView.setWebChromeClient(new WebChromeClient()
        {
            public void onProgressChanged(WebView view, int newProgress) {
                //GameLog.log("onProgressChanged:" + newProgress + ",url:" + view.getUrl());

                //if(webInUse.equals( view.getUrl()))
                //{
                    progressManager.setPrgress(newProgress);
                //}

            }
        });
        webView.setWebViewClient(new WebViewClient()
        {
            @Override
            public void onPageStarted(WebView view, String url, Bitmap favicon) {
                GameLog.log("onPageStarted url:" + url);
            }
            @Override
            public void onPageFinished(WebView view, String url) {
                progressBar.setVisibility(View.GONE);
                GameLog.log("onPageFinished url:" + url);
            }
        });

        progressgroup = findViewById(R.id.progress_background);
        progressgroup.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View view, MotionEvent motionEvent) {
                GameLog.log("touch progress background");
                return true;
            }
        });
    }



    private  void webviewsetting(WebView webView)
    {
        WebSettings settings = webView.getSettings();
        settings.setJavaScriptEnabled(true);
        settings.setDomStorageEnabled(true);
        settings.setDatabaseEnabled(true);
        settings.setAppCacheEnabled(true);
        settings.setUseWideViewPort(true);
        settings.setLayoutAlgorithm(WebSettings.LayoutAlgorithm.SINGLE_COLUMN);
        settings.setLoadWithOverviewMode(true);
        settings.setJavaScriptCanOpenWindowsAutomatically(true);
        settings.setLoadsImagesAutomatically(true);

    }

    @Override
    public void onConfigurationChanged(Configuration newConfig)
    {
        super.onConfigurationChanged(newConfig);
    }
    private boolean handleBackpressed()
    {
        if(webView.canGoBack())
        {
            webView.goBack();
            return true;
        }

        return false;
    }
    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {

        if(KeyEvent.KEYCODE_BACK == keyCode && handleBackpressed())
        {
            return true;
        }
        return super.onKeyDown(keyCode,event);
    }
}
