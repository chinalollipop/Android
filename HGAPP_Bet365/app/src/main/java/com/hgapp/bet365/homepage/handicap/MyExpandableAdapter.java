package com.hgapp.bet365.homepage.handicap;

import android.content.Context;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseExpandableListAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.hgapp.bet365.R;

public class MyExpandableAdapter extends BaseExpandableListAdapter {

    private Context mContext;
    private String[] groups;
    private String[][] children;

    public MyExpandableAdapter(Context context, String[] groups,
                               String[][] children) {
        this.mContext = context;
        this.groups = groups;
        this.children = children;

    }

    // 组的个数
    @Override
    public int getGroupCount() {

        return groups.length;
    }

    @Override
    public long getGroupId(int groupPosition) {

        return groupPosition;
    }

    // 根据组的位置，组的成员个数
    @Override
    public int getChildrenCount(int groupPosition) {
        // 根据groupPosition获取某一个组的长度
        return children[groupPosition].length;
    }

    @Override
    public Object getGroup(int groupPosition) {

        return groups[groupPosition];
    }

    @Override
    public Object getChild(int groupPosition, int childPosition) {

        return children[groupPosition][childPosition].length();
    }



    @Override
    public long getChildId(int groupPosition, int childPosition) {

        return childPosition;
    }

    @Override
    public boolean hasStableIds() {

        return false;
    }

    @Override
    public View getGroupView(int groupPosition, boolean isExpanded,
                             View convertView, ViewGroup parent) {
        GpViewHolder gpViewHolder = null;
        if (convertView == null) {
            convertView = View.inflate(mContext, R.layout.item_handicap, null);

            gpViewHolder = new GpViewHolder();
            gpViewHolder.img = (ImageView) convertView.findViewById(R.id.img);
            gpViewHolder.title = (TextView) convertView
                    .findViewById(R.id.title);
            convertView.setTag(gpViewHolder);
        } else {
            gpViewHolder = (GpViewHolder) convertView.getTag();
        }
        if(isExpanded){
            gpViewHolder.img.setImageResource(R.mipmap.icon_ex_down);
        }else{
            gpViewHolder.img.setImageResource(R.mipmap.deposit_right);
        }
        gpViewHolder.title.setText(groups[groupPosition].toString());
        return convertView;
    }

    @Override
    public View getChildView(int groupPosition, int childPosition,
                             boolean isLastChild, View convertView, ViewGroup parent) {
        GpViewHolder gpViewHolder = null;
        if (convertView == null) {
            convertView = View.inflate(mContext, R.layout.item_handicap_child, null);
            gpViewHolder = new GpViewHolder();
            gpViewHolder.img = (ImageView) convertView
                    .findViewById(R.id.child_img);
            gpViewHolder.title = (TextView) convertView
                    .findViewById(R.id.child_title);
            convertView.setTag(gpViewHolder);
        } else {
            gpViewHolder = (GpViewHolder) convertView.getTag();
        }
        onDrawable(gpViewHolder.img,childPosition);
        //gpViewHolder.img.setImageResource(R.drawable.qq_kong);
        gpViewHolder.title.setText(children[groupPosition][childPosition]
                .toString());
        return convertView;
    }

    private void onDrawable(ImageView img ,int childPosition){

        switch (childPosition){
            case 0:
                img.setImageResource(R.mipmap.icon_ex_soccer_off);
                break;
            case 1:
                img.setImageResource(R.mipmap.icon_ex_basketball_off);
                break;
            case 2:
                img.setImageResource(R.mipmap.icon_ex_tennis_off);
                break;
            case 3:
                img.setImageResource(R.mipmap.icon_ex_group);
                break;
            case 4:
                img.setImageResource(R.mipmap.icon_ex_badminton);
                break;
            case 5:
                img.setImageResource(R.mipmap.icon_ex_baseball);
                break;
            case 6:
                img.setImageResource(R.mipmap.icon_ex_bowling);
                break;
        }
    }

    @Override
    public boolean isChildSelectable(int groupPosition, int childPosition) {

        return true;
    }

    static class GpViewHolder {
        public ImageView img;
        TextView title;
    }

}