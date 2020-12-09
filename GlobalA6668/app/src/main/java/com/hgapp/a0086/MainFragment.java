package com.hgapp.a0086;

import android.content.pm.PackageInfo;
import android.os.Build;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.hgapp.a0086.common.event.LogoutEvent;
import com.hgapp.a0086.common.event.StartBrotherWithPopEvent;
import com.hgapp.a0086.common.service.DiscountsFragment;
import com.hgapp.a0086.common.service.ServiceOnlineFragment;
import com.hgapp.a0086.common.util.ACache;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.common.util.InstallHelper;
import com.hgapp.a0086.data.CheckUpgradeResult;
import com.hgapp.a0086.depositpage.DepositFragment;
import com.hgapp.a0086.homepage.HomepageFragment;
import com.hgapp.a0086.homepage.handicap.ShowMainEvent;
import com.hgapp.a0086.interfaces.IBackPressedSupport;
import com.hgapp.a0086.login.fastlogin.LoginFragment;
import com.hgapp.a0086.personpage.PersonFragment;
import com.hgapp.a0086.upgrade.CheckUpdateContract;
import com.hgapp.a0086.upgrade.UpgradeDialog;
import com.hgapp.a0086.upgrade.downunit.AppDownloadServiceBinder;
import com.hgapp.a0086.upgrade.downunit.DownloadIntent;
import com.hgapp.a0086.upgrade.downunit.DownloadProgress;
import com.hgapp.a0086.upgrade.downunit.FileDownloaderListener;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.hgapp.common.util.PackageUtil;
import com.hgapp.common.util.Timber;
import com.hgapp.common.util.ToastUtils;
import com.hgapp.common.util.Utils;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.io.File;

import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.base.BaseFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;
import me.yokeyword.sample.demo_wechat.ui.view.BottomBar;
import me.yokeyword.sample.demo_wechat.ui.view.BottomBarTab;

/**
 * Created by Daniel on 18/7/30.
 */
public class MainFragment extends BaseFragment implements CheckUpdateContract.View{
    private static final int REQ_MSG = 10;
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    private String mParam1;
    private String mParam2;
    public static final int FIRST = 0;
    public static final int SECOND = 1;
    public static final int THIRD = 2;
    public static final int FOURTH = 3;
    public static final int FIFTH = 4;

    private SupportFragment[] mFragments = new SupportFragment[5];

    private BottomBar mBottomBar;
    private int selectedPositon = 0;
    private boolean checkUpgradeDone = false;
    private CheckUpdateContract.Presenter presenter;
    private DownloadIntent intent;
    public static MainFragment newInstance() {

        Bundle args = new Bundle();

        MainFragment fragment = new MainFragment();
        fragment.setArguments(args);
        return fragment;
    }

    public static MainFragment newInstance(String param1, String param2) {
        MainFragment fragment = new MainFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, param1);
        args.putString(ARG_PARAM2, param2);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mParam1 = getArguments().getString(ARG_PARAM1);
            mParam2 = getArguments().getString(ARG_PARAM2);
        }
        presenter = Injections.inject((CheckUpdateContract.View)this,null);
        if(!checkUpgradeDone)
        {
            presenter.checkupdate();
        }
    }


    /*
     * 添加手机注册成功之后需要直接跳转到存款界面
     * 方便以后扩展，请使用SINGLETASK来启动Fragment
     * add by ak
     */
    @Override
    protected void onNewBundle(Bundle args) {
        super.onNewBundle(args);
        if (args != null) {
            mParam1 = args.getString(ARG_PARAM1);
            mParam2 = args.getString(ARG_PARAM2);
            if("person_to_deposit".equals(mParam1)){
                mBottomBar.setCurrentItem(1);
            }/*else if("person_to_home".equals(mParam1)){
                int prePosition = selectedPositon;
                showHideFragment(mFragments[0], mFragments[prePosition]);
                selectedPositon = 0;
            }*/
            /*if("registerSuccessByPhone".equals(mParam1)){
                mBottomBar.setCurrentItem(1);
            }else if("distanceLogin".equals(mParam1)){
                mBottomBar.setCurrentItem(0);
            }
            else if(ActionOnFinish.GO_ADD_BANK.equals(mParam1))
            {
                mBottomBar.setCurrentItem(3);
                int banknumber = new Local().getBankNumber();
                boolean firstBank = (banknumber==0);
                start(AddBankFragment.newInstanceForAddBank(firstBank, ActionOnFinish.ADDBANK));
            }*/
        }
    }

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_main, container, false);

        if (savedInstanceState == null) {
            mFragments[FIRST] = DiscountsFragment.newInstance();
            mFragments[SECOND] = DepositFragment.newInstance();
            mFragments[THIRD] = HomepageFragment.newInstance();
            mFragments[FOURTH] = ServiceOnlineFragment.newInstance();
            mFragments[FIFTH] = PersonFragment.newInstance();
            loadMultipleRootFragment(R.id.fl_tab_container, FIRST,
                    mFragments[FIRST],
                    mFragments[SECOND],
                    mFragments[THIRD],
                    mFragments[FOURTH],
                    mFragments[FIFTH] );
        } else {
            // 这里库已经做了Fragment恢复,所有不需要额外的处理了, 不会出现重叠问题

            // 这里我们需要拿到mFragments的引用,也可以通过getChildFragmentManager.getFragments()自行进行判断查找(效率更高些),用下面的方法查找更方便些
            mFragments[FIRST] = findChildFragment(DiscountsFragment.class);
            mFragments[SECOND] = findChildFragment(DepositFragment.class);
            mFragments[THIRD] = findChildFragment(HomepageFragment.class);
            mFragments[FOURTH] = findChildFragment(ServiceOnlineFragment.class);
            mFragments[FIFTH] = findChildFragment(PersonFragment.class);
        }

        initView(view);
        return view;
    }

    private void initView(View view) {
        EventBus.getDefault().register(this);
        mBottomBar = (BottomBar) view.findViewById(R.id.bottomBar);

        mBottomBar
                .addItem(new BottomBarTab(_mActivity, R.drawable.selector_tab_discount, getString(R.string.str_title_discounts)))
                .addItem(new BottomBarTab(_mActivity, R.drawable.selector_tab_deposit, getString(R.string.str_title_deposit)))
                .addItem(new BottomBarTab(_mActivity, R.drawable.selector_tab_homepage, getString(R.string.str_title_homepage)))
                .addItem(new BottomBarTab(_mActivity, R.drawable.selector_tab_withdraw, getString(R.string.str_title_withdraw)))
                .addItem(new BottomBarTab(_mActivity, R.drawable.selector_tab_person, getString(R.string.str_title_person)));
        // 模拟未读消息
        //mBottomBar.getItem(FIRST).setUnreadCount(9);
        mBottomBar.setCurrentItem(2);
        mBottomBar.setOnTabSelectedListener(new BottomBar.OnTabSelectedListener() {
            @Override
            public void onTabSelected(int position, int prePosition) {
                if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))&&position==1){
                    mBottomBar.setCurrentItem(prePosition);
                    showMessage(getString(R.string.comm_pls_register_real_acccount));
                    return;
                }
                try{
                    String userStatus = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_STATUS+ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT));
                GameLog.log("用户的登录状态 [ 1登录成功 ] [ 0 未登录 ] ："+userStatus+" 当前的位置是 "+prePosition+" 目前位置是 "+position);
                if(Check.isEmpty(userStatus)){
                    userStatus = "0";
                }
                if("0".equals(userStatus)){//未登录的情况下是看不到其他界面的 ，调整到登录页去 &&position!=2
                    if(position==0||position==2||position==3){
                        showHideFragment(mFragments[position], mFragments[prePosition]);
                    }else{
                        showHideFragment(mFragments[2],null);
                        mBottomBar.setCurrentItem(2);
                        EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    }
                    return;
                }

                showHideFragment(mFragments[position], mFragments[prePosition]);
               }catch (Exception e){
                   GameLog.log("点击失败的展示："+e.toString());
               }
            }

            @Override
            public void onTabUnselected(int position) {

            }

            @Override
            public void onTabReselected(int position) {
                // 这里推荐使用EventBus来实现 -> 解耦
                // 在FirstPagerFragment,FirstHomeFragment中接收, 因为是嵌套的Fragment
                // 主要为了交互: 重选tab 如果列表不在顶部则移动到顶部,如果已经在顶部,则刷新
                //EventBus.getDefault().post(new TabSelectedEvent(position)); 要不要这行无所谓

            }
        });
    }

    @Override
    protected void onFragmentResult(int requestCode, int resultCode, Bundle data) {
        super.onFragmentResult(requestCode, resultCode, data);
        if (requestCode == REQ_MSG && resultCode == RESULT_OK) {
        }
    }

    /**
     * start other BrotherFragment
     */
    @Subscribe
    public void startBrother(StartBrotherEvent event) {
        start(event.targetFragment,event.launchmode);
    }

    @Subscribe
    public void onShowMainEvent(ShowMainEvent event)
    {
        setSelectTab(event.getShowNumber());
    }

    @Subscribe
    public void onLogoutEvent(LogoutEvent event)
    {
       setSelectTab(2);
    }
    @Subscribe
    public void startBrotherWithPop(StartBrotherWithPopEvent event)
    {
        Timber.d("收到事件 StartBrotherWithPopEvent --> %s",event.getClass().getName());
        getTopFragment().startWithPop(event.targetFragment);
    }
    @Override
    public void onDestroyView() {
        EventBus.getDefault().unregister(this);
        super.onDestroyView();
    }

    @Override
    public boolean onBackPressedSupport()
    {
        //处理root fragment的返回事件，因为root fragment被嵌套在MainFragment.返回事件只能传达到MainFragment.
        //所以，这里做一个事件传递
        if(selectedPositon >= 0  && selectedPositon < mFragments.length)
        {
            if(mFragments[selectedPositon] instanceof IBackPressedSupport)
            {
                IBackPressedSupport backPressedSupport = (IBackPressedSupport)mFragments[selectedPositon];
                boolean handled = backPressedSupport.backPressedHandled();
                if(handled)
                {
                    return true;
                }
            }
        }
        return false;
    }

    @Override
    public void onStop()
    {
        super.onStop();
        //presenter.destroy();
        //AppDownloadServiceBinder.getBinder().unregisterListener(getContext().getPackageName());
    }
    @Override
    public boolean wantShowMessage() {
        return false;
    }

    @Override
    public void setData(CheckUpgradeResult checkUpgradeResult) {

        if(null != checkUpgradeResult)
        {
            ACache.get(getContext()).put(HGConstant.USERNAME_SERVICE_URL,checkUpgradeResult.getService_meiqia() );
            ACache.get(getContext()).put("service_meiqia2",checkUpgradeResult.getService_meiqia2() );
            ACache.get(getContext()).put(HGConstant.USERNAME_SERVICE_URL_QQ,checkUpgradeResult.getService_qq() );
            ACache.get(getContext()).put(HGConstant.USERNAME_SERVICE_URL_WECHAT,checkUpgradeResult.getService_wechat() );
            ACache.get(getContext()).put(HGConstant.USERNAME_SERVICE_URL_WECHAT+"_url",checkUpgradeResult.getService_wechat_url() );
            ACache.get(getContext()).put("guest_login_must_input_phone",checkUpgradeResult.getGuest_login_must_input_phone() );
            ACache.get(getContext()).put("login_must_tpl_name",checkUpgradeResult.getTpl_name());
            ACache.get(getContext()).put("signSwitch",checkUpgradeResult.getSignSwitch() );

            //EventBus.getDefault().post(checkUpgradeResult);
            checkUpgradeDone = true;
            PackageInfo packageInfo =  PackageUtil.getAppPackageInfo(Utils.getContext());
            if(null == packageInfo)
            {
                Timber.e("检查更新失败，获取不到app版本号");
                throw new RuntimeException("检查更新失败，获取不到app版本号");
            }
            String localver = packageInfo.versionName;
            GameLog.log("当前APP的版本号是："+localver);
            if(!localver.equals(checkUpgradeResult.getVersion())){
                //onDownLoadAPP(checkUpgradeResult);
                UpgradeDialog.newInstance(checkUpgradeResult).show(getFragmentManager());
            }
            GameLog.log("getDescription "+checkUpgradeResult.getDescription());
            ACache.get(getContext()).put("redPocketOpen",checkUpgradeResult.getRedPocketOpen() );
            ACache.get(getContext()).put("telOn",checkUpgradeResult.getTelOn() );
            ACache.get(getContext()).put("chatOn",checkUpgradeResult.getChatOn() );
            ACache.get(getContext()).put("qqOn",checkUpgradeResult.getQqOn() );
            ACache.get(getContext()).put("android_baodu",checkUpgradeResult.getAndroid_baodu());
            ACache.get(getContext()).put("login_verify_realname",checkUpgradeResult.getLogin_verify_realname());
        }
    }

    private void onDownLoadAPP(CheckUpgradeResult checkUpgradeResult){
        AppDownloadServiceBinder.getBinder().bind();
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            boolean hasInstallPermission = getActivity().getPackageManager().canRequestPackageInstalls();
            if (!hasInstallPermission) {
                InstallHelper.startInstallPermissionSettingActivity(getContext());
                return;
            }
        }
        checkUpgradeResult.setFile_path(checkUpgradeResult.getFile_path());
        //checkUpgradeResult.setFile_path("https://hg00086.firebaseapp.com/app-release.apk");
        intent = new DownloadIntent();
        intent.packageName = getContext().getPackageName();
        intent.dir = getContext().getCacheDir().getAbsolutePath();
        intent.fileName = getContext().getPackageName()+".apk";
        intent.tempFileName = "temp" + intent.fileName;
        intent.url = checkUpgradeResult.getFile_path();
        File file = new File(intent.dir,intent.fileName);
        String lVersion = InstallHelper.apkInfoVersion(intent.dir+"/"+intent.fileName,getContext());
        GameLog.log("\"本地文件的版本号是："+lVersion);
        if(lVersion.equals(checkUpgradeResult.getVersion())){
            showMessage(getString(R.string.comm_app_upgrade_now));
            if (file.exists()) {
                GameLog.log("\"安装app时发现文件已经存在"+file.getAbsolutePath());
                InstallHelper.attemptIntallApp(getContext(),file);
                //如果为文件，直接删除
                /*if(file.isFile()){
                    file.delete();
                }*/
                return;
            }
        }
        AppDownloadServiceBinder binder = AppDownloadServiceBinder.getBinder();
        binder.registerListener(intent.packageName,fileDownloaderListener);
        binder.downloadUpgradeApp(intent);

    }

    private FileDownloaderListener fileDownloaderListener = new FileDownloaderListener()
    {

        @Override
        public void onBegin(String packagename) {
        }

        @Override
        public void onProgress(DownloadProgress progress) {
            Timber.i("升级进度:%s",progress.toString());
            GameLog.log("升级进度 totalSize ["+progress.totalSize+ "]  sofarSize ["+progress.sofarSize+"] percent  -> "+progress.percent);
            /*progressBar.setProgress(progress.percent);
            tvSize.setText(progress.getSofarSizeInM()+"/" + progress.getTotalSizeInM());*/
        }

        @Override
        public void onComplete(String packagename) {
            showMessage(getString(R.string.comm_app_upgrade_now));
            if(null != intent)
            {
                File file = new File(intent.dir,intent.fileName);
                InstallHelper.attemptIntallApp(getContext(),file);
            }
            AppDownloadServiceBinder.getBinder().unbind();
        }

        @Override
        public void onError(String packagename, int errcode) {
        }
    };

    @Override
    public void showMessage(String message) {
        ToastUtils.showLongToast(message);
    }

    @Override
    public void setStart(int action) {

    }

    @Override
    public void setError(int action, int errcode) {

    }

    @Override
    public void setError(int action, String errString) {

    }

    @Override
    public void setComplete(int action) {

    }

    @Override
    public void setPresenter(CheckUpdateContract.Presenter presenter) {
        this.presenter = presenter;
    }

    private void setSelectTab(int position)
    {
        mBottomBar.setCurrentItem(position);
        int prePosition = selectedPositon;
        showHideFragment(mFragments[position], mFragments[prePosition]);
        selectedPositon = position;
        GameLog.log("重新校验。。。。。");
    }
}
