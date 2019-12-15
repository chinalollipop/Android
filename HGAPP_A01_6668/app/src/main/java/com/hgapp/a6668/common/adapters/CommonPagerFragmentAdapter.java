package com.hgapp.a6668.common.adapters;

import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentPagerAdapter;

/**
 * Created by Nereus on 2017/5/22.
 */

public class CommonPagerFragmentAdapter extends FragmentPagerAdapter {
    private String[] mTab;
    private Fragment[] fragments;
    public CommonPagerFragmentAdapter(FragmentManager fm,String[] tab,Fragment[] fragments) {
        super(fm);
        if(null == tab)
        {
            throw  new RuntimeException("tab cannot be null");
        }

        if(null == fragments)
        {
            throw new RuntimeException("fragments can not be null");
        }

        if(tab.length != fragments.length)
        {
            throw new RuntimeException("the length of tab and fragments should equal");
        }
        this.mTab = tab;
        this.fragments = fragments;
    }

    @Override
    public Fragment getItem(int position) {
        return fragments[position];
    }

    @Override
    public int getCount() {
        return mTab.length;
    }

    @Override
    public CharSequence getPageTitle(int position) {
        return mTab[position];
    }
}