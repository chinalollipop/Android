package com.hgapp.a6668.homepage.cplist;

import com.hgapp.a6668.homepage.cplist.events.CPOrderList;
import com.hgapp.a6668.homepage.handicap.leaguedetail.ComPassListData;
import com.hgapp.common.util.GameLog;

import java.util.ArrayList;
import java.util.HashSet;

public class CPBetManager {
    ArrayList<CPOrderList> listData = new ArrayList<CPOrderList>();
    HashSet<String> hashSetGid = new HashSet<>();
    private volatile static CPBetManager singleton;

    private CPBetManager() {
    }

    public static CPBetManager getSingleton() {
        if (singleton == null) {
            synchronized (CPBetManager.class) {
                if (singleton == null) {
                    singleton = new CPBetManager();
                }
            }
        }
        return singleton;
    }

    public void onRemoveItem(String gid) {
        int size = listData.size();
        for (int k = 0; k < size; ++k) {
            CPOrderList comPassListData1 = listData.get(k);
            if (gid.equals(comPassListData1.otherName)) {//如果有这个gid的游戏，再看这个gid是否一致，
                listData.remove(comPassListData1);
                hashSetGid.remove(comPassListData1.otherName);
                --size;
            }
        }
    }

    public boolean inContain( String otherName){
        if(hashSetGid.contains(otherName)){
            return true;
        }
        return false;
    }

    public void onAddData(String position, String gid, String gName,String rate, String otherName) {
        if(hashSetGid.contains(otherName)){
            int size = listData.size();
            for (int k = 0; k < size; ++k) {
                CPOrderList comPassListData1 = listData.get(k);
                if (otherName.equals(comPassListData1.otherName)) {
                    hashSetGid.remove(otherName);
                    listData.remove(comPassListData1);
                    --size;
                } /*else {
                    listData.remove(comPassListData1);
                    listData.add(new CPOrderList(comPassListData1.position, comPassListData1.gid, comPassListData1.gName,rate, comPassListData1.otherName));
                }*/
            }
        }else{//没有就增加一个
            hashSetGid.add(otherName);
            listData.add(new CPOrderList(position, gid, gName,rate, otherName));
        }

    }

    public int onListSize() {
        return hashSetGid.size();
    }

    public void onClearData() {
        hashSetGid.clear();
        listData.clear();
    }

    public HashSet<String> onShowHash() {
        return hashSetGid;
    }

    private boolean onCheckHave(String jointdata) {
        boolean isHave = false;
        if (hashSetGid.contains(jointdata)) {
            isHave = true;
        }
        return isHave;
    }

    public ArrayList<CPOrderList> onShowViewListData() {
        return listData;
    }

}
