package com.pkpkpk.newatoa;

import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.widget.Button;


public class LaunchActivity extends AppCompatActivity {

    private boolean ifStop = false;
    Button button;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_launch);
        button = (Button)findViewById(R.id.retry);
        button.postDelayed(new Runnable() {
            @Override
            public void run() {
                enterMain();
            }
        },1500);
        //onGetAvailableDomain();
    }
    public void enterMain()
    {
        startActivity(new Intent(LaunchActivity.this,MainActivity.class));
        finish();
    }


}
