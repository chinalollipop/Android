package com.qpweb.a01.ui.home.agency;

import android.annotation.SuppressLint;
import android.content.Context;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.qpweb.a01.R;

@SuppressLint("ValidFragment")
public class SimpleCardFragment extends Fragment {
    private String mTitle;

    public static SimpleCardFragment getInstance(String title) {
        SimpleCardFragment sf = new SimpleCardFragment();
        sf.mTitle = title;
        return sf;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        /*View v = inflater.inflate(R.layout.fr_simple_card, null);
        TextView card_title_tv = (TextView) v.findViewById(R.id.card_title_tv);*/
        TextView card_title_tv  = new TextView(getContext());
        card_title_tv.setText(mTitle);
        return card_title_tv;
    }
}