package com.hgapp.a0086.homepage.handicap.leaguedetail.zhbet;

import com.hgapp.a0086.homepage.handicap.leaguedetail.ComPassListData;
import com.hgapp.common.util.GameLog;

import java.util.ArrayList;
import java.util.HashSet;

public class ZHBetManager {
    ArrayList<ComPassListData> listData = new ArrayList<>();
    HashSet<String> hashSetGid  = new HashSet<>();
    private volatile static ZHBetManager singleton;
    private ZHBetManager (){}
    public static ZHBetManager getSingleton() {
        if (singleton == null) {
            synchronized (ZHBetManager.class) {
                if (singleton == null) {
                    singleton = new ZHBetManager();
                }
            }
        }
        return singleton;
    }

    public void onRemoveItem(String gid){
        int size = listData.size();
        for(int k=0;k<size;++k){
            ComPassListData  comPassListData1 = listData.get(k);
            if(gid.equals(comPassListData1.gid)){//如果有这个gid的游戏，再看这个gid是否一致，
                listData.remove(comPassListData1);
                hashSetGid.remove(comPassListData1.jointdata);
                --size;
            }
        }
    }

    public void onAddData(String jointdata,String gid, String gid_fs, String method_type,int checked){
        //GameLog.log("jointdata "+jointdata+" gid "+gid+" method_type "+method_type+" checked "+checked);
        if(hashSetGid.contains(jointdata)){//先判断是否包含这个游戏名字
            int size = listData.size();
            for(int k=0;k<size;++k){
                ComPassListData  comPassListData1 = listData.get(k);
               /* GameLog.log("jointdata "+jointdata+" gid1 "+gid+" gid_fs "+gid_fs+" method_type1 "+method_type+" checked "+comPassListData1.checked);
                GameLog.log("jointdata2 "+comPassListData1.jointdata+" gid2 "+comPassListData1.gid+" gid_fs "+comPassListData1.gid_fs+" method_type2 "+comPassListData1.method_type+" checked2 "+comPassListData1.checked);
               */ if(jointdata.equals(comPassListData1.jointdata)){//如果有这个名字的游戏，再看这个gid是否一致，
                    if(gid.equals(comPassListData1.gid)){
                        if(gid.equals(comPassListData1.gid_fs)){
                            if(method_type.equals(comPassListData1.method_type)){
                                if(gid_fs.equals(comPassListData1.gid_fs)){
                                    hashSetGid.remove(jointdata);
                                    listData.remove(comPassListData1);
                                }else{
                                    listData.remove(comPassListData1);
                                    listData.add(new ComPassListData(jointdata,gid,gid_fs,method_type,checked));
                                }

                            }else{
                                listData.remove(comPassListData1);
                                listData.add(new ComPassListData(jointdata,gid,gid_fs,method_type,checked));
                            }

                        }else{
                            if(gid_fs.equals(comPassListData1.gid_fs)){
                                if(method_type.equals(comPassListData1.method_type)){
                                    hashSetGid.remove(jointdata);
                                    listData.remove(comPassListData1);
                                }else{
                                    listData.remove(comPassListData1);
                                    listData.add(new ComPassListData(jointdata,gid,gid_fs,method_type,checked));
                                }
                            }else{
                                listData.remove(comPassListData1);
                                listData.add(new ComPassListData(jointdata,gid,gid_fs,method_type,checked));
                            }

                        }

                        --size;
                    }else{
                        listData.remove(comPassListData1);
                        listData.add(new ComPassListData(jointdata,gid,gid_fs,method_type,checked));
                    }
                }
            }
        }else{//没有就增加一个
            hashSetGid.add(jointdata);
            listData.add(new ComPassListData(jointdata,gid,gid_fs,method_type,checked));
        }

         /*Iterator<ComPassListData> comPassListData = listData.iterator();
            while(comPassListData.hasNext()) {
                ComPassListData  comPassListData1 = comPassListData.next();
                if(gid.equals(comPassListData1.gid)){//如果有这个gid的游戏，再看这个gid是否一致，
                    if(method_type.equals(comPassListData1.method_type)){
                        hashSetGid.remove(gid);
                        listData.remove(comPassListData1);
                    }else{
                        listData.remove(comPassListData1);
                        listData.add(new ComPassListData(gid,method_type));
                    }
                }
            }*/
            /*for(ComPassListData comPassListData: listData){
                GameLog.log("原 gid "+gid+" listData gid "+comPassListData.gid);
                if(gid.equals(comPassListData.gid)){//如果有这个gid的游戏，再看这个gid是否一致，
                    if(method_type.equals(comPassListData.method_type)){
                        hashSetGid.remove(gid);
                        listData.remove(comPassListData);
                    }else{
                        listData.remove(comPassListData);
                        listData.add(new ComPassListData(gid,method_type));
                    }
                }
            }*/
    }

    public int onListSize(){
        return hashSetGid.size();
    }

    public void onClearData(){
        hashSetGid.clear();
        listData.clear();
    }

    public HashSet<String> onShowHash(){
        return hashSetGid;
    }

    private boolean onCheckHave(String jointdata){
        boolean isHave=false;
        if(hashSetGid.contains(jointdata)) {
            isHave = true;
        }
        return isHave;
    }

    public ArrayList<ComPassListData> onShowViewListData(){
        return listData;
    }

}
