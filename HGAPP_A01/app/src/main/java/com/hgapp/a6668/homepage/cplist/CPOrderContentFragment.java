package com.hgapp.a6668.homepage.cplist;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;

import com.brioal.swipemenu.view.SwipeMenu;
import com.google.gson.Gson;
import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.adapters.AutoSizeAdapter;
import com.hgapp.a6668.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a6668.common.util.ArrayListHelper;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.util.TimeHelper;
import com.hgapp.a6668.data.AGGameLoginResult;
import com.hgapp.a6668.data.AGLiveResult;
import com.hgapp.a6668.data.CPBJSCResult2;
import com.hgapp.a6668.data.CheckAgLiveResult;
import com.hgapp.a6668.data.PersonBalanceResult;
import com.hgapp.a6668.homepage.HomePageIcon;
import com.hgapp.a6668.homepage.aglist.AGListContract;
import com.hgapp.a6668.homepage.cplist.events.LeftEvents;
import com.hgapp.common.util.GameLog;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import butterknife.BindView;
import butterknife.OnClick;
import butterknife.Unbinder;

public class CPOrderContentFragment extends HGBaseFragment implements AGListContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.rvContentOrder)
    RecyclerView rvContentOrder;
    AGListContract.Presenter presenter;
    private String userName, userMoney, fshowtype;
    private static List<CPOrderAllResult> allResultList = new ArrayList<CPOrderAllResult>();
    public static CPOrderContentFragment newInstance(List<String> param1) {
        CPOrderContentFragment fragment = new CPOrderContentFragment();
        Bundle args = new Bundle();
        args.putStringArrayList(ARG_PARAM1, ArrayListHelper.convertListToArrayList(param1));
        Injections.inject(null, fragment);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            userName = getArguments().getStringArrayList(ARG_PARAM1).get(0);
            userMoney = getArguments().getStringArrayList(ARG_PARAM1).get(1);
            fshowtype = getArguments().getStringArrayList(ARG_PARAM1).get(2);// 用以判断是电子还是真人
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_cp_order_content;
    }

    public String getFromAssets(String fileName) {
        try {
            InputStreamReader inputReader = new InputStreamReader(getResources().getAssets().open(fileName));
            BufferedReader bufReader = new BufferedReader(inputReader);
            String line = "";
            String Result = "";
            while ((line = bufReader.readLine()) != null)
                Result += line;
            return Result;
        } catch (Exception e) {
            e.printStackTrace();
        }
        return "";
    }

    private void BJPK10(CPBJSCResult2 cpbjscResult){

        for (int k = 0; k < 2; ++k) {
            CPOrderAllResult allResult = new CPOrderAllResult();
            if(k==0){
                List<CPOrderContentListResult> CPOrderContentListResult = new ArrayList<CPOrderContentListResult>();
                for (int l = 0; l < 11; ++l) {
                    CPOrderContentListResult cpOrderContentListResult = new CPOrderContentListResult();
                    switch (l) {
                        case 0:
                            cpOrderContentListResult.setOrderContentListName("冠亚和");
                            List<CPOrderContentResult> cpOrderContentResultList = new ArrayList<>();
                            for (int j = 0; j < 4; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("冠军大");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_3017());
                                        cpOrderContentResult0.setOrderId("3017");
                                        cpOrderContentResultList.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("冠军小");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_3018());
                                        cpOrderContentResult1.setOrderId("3018");
                                        cpOrderContentResultList.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("冠军单");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_3019());
                                        cpOrderContentResult2.setOrderId("3019");
                                        cpOrderContentResultList.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("冠军双");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_3020());
                                        cpOrderContentResult3.setOrderId("3020");
                                        cpOrderContentResultList.add(cpOrderContentResult3);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 1:
                            cpOrderContentListResult.setOrderContentListName("冠军");
                            List<CPOrderContentResult> cpOrderContentResultList1 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30013011());
                                        cpOrderContentResult0.setOrderId("3001-3011");
                                        cpOrderContentResultList1.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3001-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30013012());
                                        cpOrderContentResultList1.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("龙");
                                        cpOrderContentResult2.setOrderId("3001-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30013013());
                                        cpOrderContentResultList1.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("双");
                                        cpOrderContentResult3.setOrderId("3001-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30013014());
                                        cpOrderContentResultList1.add(cpOrderContentResult3);
                                        break;
                                    case 4:
                                        CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                                        cpOrderContentResult4.setOrderName("小");
                                        cpOrderContentResult4.setOrderId("3001-3015");
                                        cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30013015());
                                        cpOrderContentResultList1.add(cpOrderContentResult4);
                                        break;
                                    case 5:
                                        CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                                        cpOrderContentResult5.setOrderName("虎");
                                        cpOrderContentResult5.setOrderId("3001-3016");
                                        cpOrderContentResult5.setOrderState(cpbjscResult.getdata_30013016());
                                        cpOrderContentResultList1.add(cpOrderContentResult5);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList1);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 2:
                            cpOrderContentListResult.setOrderContentListName("亚军");
                            List<CPOrderContentResult> cpOrderContentResultList2 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderId("3002-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30023011());
                                        cpOrderContentResultList2.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3002-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30023012());
                                        cpOrderContentResultList2.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("龙");
                                        cpOrderContentResult2.setOrderId("3002-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30023013());
                                        cpOrderContentResultList2.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("双");
                                        cpOrderContentResult3.setOrderId("3002-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30023014());
                                        cpOrderContentResultList2.add(cpOrderContentResult3);
                                        break;
                                    case 4:
                                        CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                                        cpOrderContentResult4.setOrderName("小");
                                        cpOrderContentResult4.setOrderId("3002-3015");
                                        cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30023015());
                                        cpOrderContentResultList2.add(cpOrderContentResult4);
                                        break;
                                    case 5:
                                        CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                                        cpOrderContentResult5.setOrderName("虎");
                                        cpOrderContentResult5.setOrderId("3002-3016");
                                        cpOrderContentResult5.setOrderState(cpbjscResult.getdata_30023016());
                                        cpOrderContentResultList2.add(cpOrderContentResult5);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList2);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 3:
                            cpOrderContentListResult.setOrderContentListName("第三名");
                            List<CPOrderContentResult> cpOrderContentResultList3 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderId("3003-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30033011());
                                        cpOrderContentResultList3.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3003-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30033012());
                                        cpOrderContentResultList3.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("龙");
                                        cpOrderContentResult2.setOrderId("3003-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30033013());
                                        cpOrderContentResultList3.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("双");
                                        cpOrderContentResult3.setOrderId("3003-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30033014());
                                        cpOrderContentResultList3.add(cpOrderContentResult3);
                                        break;
                                    case 4:
                                        CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                                        cpOrderContentResult4.setOrderName("小");
                                        cpOrderContentResult4.setOrderId("3003-3015");
                                        cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30033015());
                                        cpOrderContentResultList3.add(cpOrderContentResult4);
                                        break;
                                    case 5:
                                        CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                                        cpOrderContentResult5.setOrderName("虎");
                                        cpOrderContentResult5.setOrderId("3003-3016");
                                        cpOrderContentResult5.setOrderState(cpbjscResult.getdata_30033016());
                                        cpOrderContentResultList3.add(cpOrderContentResult5);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList3);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 4:
                            cpOrderContentListResult.setOrderContentListName("第四名");
                            List<CPOrderContentResult> cpOrderContentResultList4 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderId("3004-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30043011());
                                        cpOrderContentResultList4.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3004-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30043012());
                                        cpOrderContentResultList4.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("龙");
                                        cpOrderContentResult2.setOrderId("3004-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30043013());
                                        cpOrderContentResultList4.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("双");
                                        cpOrderContentResult3.setOrderId("3004-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30043014());
                                        cpOrderContentResultList4.add(cpOrderContentResult3);
                                        break;
                                    case 4:
                                        CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                                        cpOrderContentResult4.setOrderName("小");
                                        cpOrderContentResult4.setOrderId("3004-3015");
                                        cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30043015());
                                        cpOrderContentResultList4.add(cpOrderContentResult4);
                                        break;
                                    case 5:
                                        CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                                        cpOrderContentResult5.setOrderName("虎");
                                        cpOrderContentResult5.setOrderId("3004-3016");
                                        cpOrderContentResult5.setOrderState(cpbjscResult.getdata_30043016());
                                        cpOrderContentResultList4.add(cpOrderContentResult5);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList4);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 5:
                            cpOrderContentListResult.setOrderContentListName("第五名");
                            List<CPOrderContentResult> cpOrderContentResultList5 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderId("3005-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30053011());
                                        cpOrderContentResultList5.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3005-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30053012());
                                        cpOrderContentResultList5.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("龙");
                                        cpOrderContentResult2.setOrderId("3005-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30053013());
                                        cpOrderContentResultList5.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("双");
                                        cpOrderContentResult3.setOrderId("3005-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30053014());
                                        cpOrderContentResultList5.add(cpOrderContentResult3);
                                        break;
                                    case 4:
                                        CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                                        cpOrderContentResult4.setOrderName("小");
                                        cpOrderContentResult4.setOrderId("3005-3015");
                                        cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30053015());
                                        cpOrderContentResultList5.add(cpOrderContentResult4);
                                        break;
                                    case 5:
                                        CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                                        cpOrderContentResult5.setOrderName("虎");
                                        cpOrderContentResult5.setOrderId("3005-3016");
                                        cpOrderContentResult5.setOrderState(cpbjscResult.getdata_30053016());
                                        cpOrderContentResultList5.add(cpOrderContentResult5);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList5);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 6:
                            cpOrderContentListResult.setOrderContentListName("第六名");
                            List<CPOrderContentResult> cpOrderContentResultList6 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderId("3006-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30063011());
                                        cpOrderContentResultList6.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3006-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30063012());
                                        cpOrderContentResultList6.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("双");
                                        cpOrderContentResult2.setOrderId("3006-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30063013());
                                        cpOrderContentResultList6.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("小");
                                        cpOrderContentResult3.setOrderId("3006-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30063014());
                                        cpOrderContentResultList6.add(cpOrderContentResult3);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList6);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 7:
                            cpOrderContentListResult.setOrderContentListName("第七名");
                            List<CPOrderContentResult> cpOrderContentResultList7 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderId("3007-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30073011());
                                        cpOrderContentResultList7.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3007-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30073012());
                                        cpOrderContentResultList7.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("双");
                                        cpOrderContentResult2.setOrderId("3007-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30073013());
                                        cpOrderContentResultList7.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("小");
                                        cpOrderContentResult3.setOrderId("3007-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30073014());
                                        cpOrderContentResultList7.add(cpOrderContentResult3);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList7);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 8:
                            cpOrderContentListResult.setOrderContentListName("第八名");
                            List<CPOrderContentResult> cpOrderContentResultList8 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderId("3008-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30083011());
                                        cpOrderContentResultList8.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3008-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30083012());
                                        cpOrderContentResultList8.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("双");
                                        cpOrderContentResult2.setOrderId("3008-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30083013());
                                        cpOrderContentResultList8.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("小");
                                        cpOrderContentResult3.setOrderId("3008-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30083014());
                                        cpOrderContentResultList8.add(cpOrderContentResult3);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList8);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 9:
                            cpOrderContentListResult.setOrderContentListName("第九名");
                            List<CPOrderContentResult> cpOrderContentResultList9 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderId("3009-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30093011());
                                        cpOrderContentResultList9.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3009-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30093012());
                                        cpOrderContentResultList9.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("双");
                                        cpOrderContentResult2.setOrderId("3009-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30093013());
                                        cpOrderContentResultList9.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("小");
                                        cpOrderContentResult3.setOrderId("3009-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30093014());
                                        cpOrderContentResultList9.add(cpOrderContentResult3);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList9);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 10:
                            cpOrderContentListResult.setOrderContentListName("第⑩名");
                            List<CPOrderContentResult> cpOrderContentResultList10 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderId("3010-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30103011());
                                        cpOrderContentResultList10.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3010-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30103012());
                                        cpOrderContentResultList10.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("双");
                                        cpOrderContentResult2.setOrderId("3010-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30103013());
                                        cpOrderContentResultList10.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("小");
                                        cpOrderContentResult3.setOrderId("3010-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30103014());
                                        cpOrderContentResultList10.add(cpOrderContentResult3);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList10);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                    }
                }
                allResult.setOrderAllName("两面");
                allResult.setData(CPOrderContentListResult);
            }else if(k==1){
                List<CPOrderContentListResult> CPOrderContentListResult = new ArrayList<CPOrderContentListResult>();
                allResult.setOrderAllName("冠亚和");
                CPOrderContentListResult cpOrderContentListResult = new CPOrderContentListResult();
                cpOrderContentListResult.setOrderContentListName("冠、亚军 组合");

                List<CPOrderContentResult> cpOrderContentResultList = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                cpOrderContentResult0.setOrderName("冠军大");
                cpOrderContentResult0.setOrderState(cpbjscResult.getdata_3017());
                cpOrderContentResult0.setOrderId("3017");
                cpOrderContentResultList.add(cpOrderContentResult0);

                CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                cpOrderContentResult1.setOrderName("冠军小");
                cpOrderContentResult1.setOrderState(cpbjscResult.getdata_3018());
                cpOrderContentResult1.setOrderId("3018");
                cpOrderContentResultList.add(cpOrderContentResult1);

                CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                cpOrderContentResult2.setOrderName("冠军单");
                cpOrderContentResult2.setOrderState(cpbjscResult.getdata_3019());
                cpOrderContentResult2.setOrderId("3019");
                cpOrderContentResultList.add(cpOrderContentResult2);

                CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                cpOrderContentResult3.setOrderName("冠军双");
                cpOrderContentResult3.setOrderState(cpbjscResult.getdata_3020());
                cpOrderContentResult3.setOrderId("3020");
                cpOrderContentResultList.add(cpOrderContentResult3);

                CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                cpOrderContentResult4.setOrderName("3");
                cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult4.setOrderId("3021-3");
                cpOrderContentResultList.add(cpOrderContentResult4);

                CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                cpOrderContentResult5.setOrderName("4");
                cpOrderContentResult5.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult5.setOrderId("3021-4");
                cpOrderContentResultList.add(cpOrderContentResult5);

                CPOrderContentResult cpOrderContentResult6 = new CPOrderContentResult();
                cpOrderContentResult6.setOrderName("5");
                cpOrderContentResult6.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult6.setOrderId("3021-5");
                cpOrderContentResultList.add(cpOrderContentResult6);

                CPOrderContentResult cpOrderContentResult7 = new CPOrderContentResult();
                cpOrderContentResult7.setOrderName("6");
                cpOrderContentResult7.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult7.setOrderId("3021-6");
                cpOrderContentResultList.add(cpOrderContentResult7);

                CPOrderContentResult cpOrderContentResult8 = new CPOrderContentResult();
                cpOrderContentResult8.setOrderName("7");
                cpOrderContentResult8.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult8.setOrderId("3021-7");
                cpOrderContentResultList.add(cpOrderContentResult8);

                CPOrderContentResult cpOrderContentResult9 = new CPOrderContentResult();
                cpOrderContentResult9.setOrderName("8");
                cpOrderContentResult9.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult9.setOrderId("3021-8");
                cpOrderContentResultList.add(cpOrderContentResult9);

                CPOrderContentResult cpOrderContentResult10 = new CPOrderContentResult();
                cpOrderContentResult10.setOrderName("9");
                cpOrderContentResult10.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult10.setOrderId("3021-9");
                cpOrderContentResultList.add(cpOrderContentResult10);

                CPOrderContentResult cpOrderContentResult11 = new CPOrderContentResult();
                cpOrderContentResult11.setOrderName("10");
                cpOrderContentResult11.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult11.setOrderId("3021-10");
                cpOrderContentResultList.add(cpOrderContentResult11);

                CPOrderContentResult cpOrderContentResult12 = new CPOrderContentResult();
                cpOrderContentResult12.setOrderName("11");
                cpOrderContentResult12.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult12.setOrderId("3021-11");
                cpOrderContentResultList.add(cpOrderContentResult12);

                CPOrderContentResult cpOrderContentResult13 = new CPOrderContentResult();
                cpOrderContentResult13.setOrderName("12");
                cpOrderContentResult13.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult13.setOrderId("3021-12");
                cpOrderContentResultList.add(cpOrderContentResult13);

                CPOrderContentResult cpOrderContentResult14 = new CPOrderContentResult();
                cpOrderContentResult14.setOrderName("13");
                cpOrderContentResult14.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult14.setOrderId("3021-13");
                cpOrderContentResultList.add(cpOrderContentResult14);

                CPOrderContentResult cpOrderContentResult15 = new CPOrderContentResult();
                cpOrderContentResult15.setOrderName("14");
                cpOrderContentResult15.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult15.setOrderId("3021-14");
                cpOrderContentResultList.add(cpOrderContentResult15);

                CPOrderContentResult cpOrderContentResult16 = new CPOrderContentResult();
                cpOrderContentResult16.setOrderName("15");
                cpOrderContentResult16.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult16.setOrderId("3021-15");
                cpOrderContentResultList.add(cpOrderContentResult16);

                CPOrderContentResult cpOrderContentResult17 = new CPOrderContentResult();
                cpOrderContentResult17.setOrderName("16");
                cpOrderContentResult17.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult17.setOrderId("3021-16");
                cpOrderContentResultList.add(cpOrderContentResult17);

                CPOrderContentResult cpOrderContentResult18 = new CPOrderContentResult();
                cpOrderContentResult18.setOrderName("17");
                cpOrderContentResult18.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult18.setOrderId("3021-17");
                cpOrderContentResultList.add(cpOrderContentResult18);

                CPOrderContentResult cpOrderContentResult19 = new CPOrderContentResult();
                cpOrderContentResult19.setOrderName("18");
                cpOrderContentResult19.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult19.setOrderId("3021-18");
                cpOrderContentResultList.add(cpOrderContentResult19);

                CPOrderContentResult cpOrderContentResult20 = new CPOrderContentResult();
                cpOrderContentResult20.setOrderName("19");
                cpOrderContentResult20.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult20.setOrderId("3021-19");
                cpOrderContentResultList.add(cpOrderContentResult20);

                cpOrderContentListResult.setData(cpOrderContentResultList);
                CPOrderContentListResult.add(cpOrderContentListResult);
                allResult.setData(CPOrderContentListResult);
            }else if(k==2){
                allResult.setOrderAllName("1-5名");
            }else{
                allResult.setOrderAllName("6-10名");
            }
            allResultList.add(allResult);

        }
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        String  data = getFromAssets("data_bak.json");
        //GameLog.log("屏幕的宽度："+data);
        Gson gson = new Gson();
        CPBJSCResult2 cpbjscResult2 = gson.fromJson(data,CPBJSCResult2.class);
        //CPBJSCResult2 cpBJSCResult = JSON.parseObject(data, CPBJSCResult2.class);
        GameLog.log("屏幕的宽度："+cpbjscResult2.toString());
        BJPK10(cpbjscResult2);
        LinearLayoutManager linearLayoutManagerRight = new LinearLayoutManager(getContext(),LinearLayoutManager.VERTICAL, false);
        rvContentOrder.setLayoutManager(linearLayoutManagerRight);
        rvContentOrder.setHasFixedSize(true);
        rvContentOrder.setNestedScrollingEnabled(false);
        CPOrederListRightGameAdapter cpOrederListRightGameAdapter = new CPOrederListRightGameAdapter(getContext(), R.layout.item_cp_order_content1, allResultList.get(Integer.parseInt(userName)).getData());
        rvContentOrder.setAdapter(cpOrederListRightGameAdapter);
        rvContentOrder.scrollToPosition(0);
        cpOrederListRightGameAdapter.notifyDataSetChanged();
        //rvContentOrder.setAdapter(new Open2GameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList2));
    }


    class CPOrederListRightGameAdapter extends AutoSizeRVAdapter<CPOrderContentListResult> {
        private Context context;
        private List<CPOrderContentListResult>  datas;
        public CPOrederListRightGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        public void setDataChange(List<CPOrderContentListResult>  datas){
            this.datas = datas;
            notifyDataSetChanged();
        }

        @Override
        protected void convert(ViewHolder holder, CPOrderContentListResult data, final int position) {
            holder.setText(R.id.cpOrderContentName1, data.getOrderContentListName());
            GridLayoutManager gridLayoutManager = null;
            if(position==1||position==2||position==3||position==4||position==5){
                gridLayoutManager= new GridLayoutManager(getContext(), 3, OrientationHelper.VERTICAL, false);
            }else{
                gridLayoutManager= new GridLayoutManager(getContext(), 2, OrientationHelper.VERTICAL, false);
            }
            RecyclerView recyclerView = holder.getView(R.id.cpOrderContentList1);
            recyclerView.setLayoutManager(gridLayoutManager);
            /*recyclerView.setHasFixedSize(true);
            recyclerView.setNestedScrollingEnabled(true);*/

            // recyclerView.addItemDecoration(new GridRvItemDecoration(getContext()));
            CPOrederContentGameAdapter cpOrederContentGameAdapter = null;
            cpOrederContentGameAdapter = new CPOrederContentGameAdapter(getContext(), R.layout.item_cp_order_content2, data.getData());
            recyclerView.setAdapter(cpOrederContentGameAdapter);
        }
    }

    class CPOrederContentGameAdapter extends AutoSizeRVAdapter<CPOrderContentResult> {
        private Context context;
        private int postions;

        public CPOrederContentGameAdapter(Context context, int layoutId, List datas,int postion) {
            super(context, layoutId, datas);
            context = context;
            this.postions = postion;
        }
        public CPOrederContentGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder,final CPOrderContentResult data, final int position) {
            holder.setText(R.id.cpOrderContentName2, data.getOrderName());
            holder.setText(R.id.cpOrderContentState, data.getOrderState());
            if(data.isChecked()){
                holder.setBackgroundRes(R.id.cpOrderContentItem,R.color.cp_order_tv_clicked);
            }else{
                holder.setBackgroundRes(R.id.cpOrderContentItem,R.color.title_text);
            }
            holder.setOnClickListener(R.id.cpOrderContentItem, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if(!data.isChecked()){
                        //allResultList.get(postionAll).getData().get(postions).getData().get(position).setChecked(true);
                        data.setChecked(true);
                    }else{
                        //allResultList.get(postionAll).getData().get(postions).getData().get(position).setChecked(false);
                        data.setChecked(false);
                    }
                    GameLog.log("下注的id是："+data.getOrderId());
                    //myAdapter.notifyDataSetChanged();
                    /*cpOrederListRightGameAdapter.notifyDataSetChanged();
                    cpOrderListRight.scrollTo(10,0);*/
                }
            });
        }
    }

    class Open2GameAdapter extends AutoSizeRVAdapter<String> {
        private Context context;

        public Open2GameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, String data, final int position) {
            holder.setText(R.id.itemOrderOpen2,data);
        }
    }


    class CPOrederListViewLeftGameAdapter extends AutoSizeAdapter<CPOrderAllResult> {
        private Context context;

        public CPOrederListViewLeftGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(com.zhy.adapter.abslistview.ViewHolder holder, CPOrderAllResult data, final int position) {
            holder.setText(R.id.itemOrderLeftListTV, data.getOrderAllName());
            holder.setOnClickListener(R.id.itemOrderLeftListTV, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                }
            });
        }
    }

    class CPOrederListLeftGameAdapter extends AutoSizeRVAdapter<CPOrderAllResult> {
        private Context context;

        public CPOrederListLeftGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, CPOrderAllResult data, final int position) {
           /* if(data.isEventChecked()){
                holder.setImageResource(R.id.itemOrderLeftListIV,R.drawable.cp_circle_checked);
            }else{
                holder.setImageResource(R.id.itemOrderLeftListIV,R.drawable.cp_circle_normal);
            }*/
            holder.setText(R.id.itemOrderLeftListTV, data.getOrderAllName());
            holder.setOnClickListener(R.id.itemOrderLeftListTV, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                }
            });
        }
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void setPresenter(AGListContract.Presenter presenter) {

        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void postGoPlayGameResult(AGGameLoginResult agGameLoginResult) {

    }

    @Override
    public void postCheckAgLiveAccountResult(CheckAgLiveResult checkAgLiveResult) {

    }

    @Override
    public void postCheckAgGameAccountResult(CheckAgLiveResult checkAgLiveResult) {
    }

    @Override
    public void postPersonBalanceResult(PersonBalanceResult personBalance) {
        GameLog.log("用户的真人账户：" + personBalance.getBalance_ag());
    }

    @Override
    public void postAGGameResult(List<AGLiveResult> agLiveResult) {
        GameLog.log("游戏列表：" + agLiveResult);
    }

    @Override
    public void postCheckAgAccountResult(CheckAgLiveResult checkAgLiveResult) {

    }

    @Override
    public void postCreateAgAccountResult(CheckAgLiveResult checkAgLiveResult) {

    }


    @Subscribe
    public void onPersonBalanceResult(PersonBalanceResult personBalanceResult) {
        GameLog.log("通过发送消息得的的数据" + personBalanceResult.getBalance_ag());
    }


    @Override
    public void onDestroy() {
        super.onDestroy();
        EventBus.getDefault().unregister(this);
    }

}
