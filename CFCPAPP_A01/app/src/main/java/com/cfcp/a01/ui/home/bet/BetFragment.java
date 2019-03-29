package com.cfcp.a01.ui.home.bet;

import android.annotation.SuppressLint;
import android.inputmethodservice.KeyboardView;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.SimpleItemAnimator;
import android.text.Editable;
import android.text.TextUtils;
import android.text.TextWatcher;
import android.util.Log;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.view.animation.AccelerateDecelerateInterpolator;
import android.view.animation.Animation;
import android.view.animation.DecelerateInterpolator;
import android.view.animation.RotateAnimation;
import android.view.animation.TranslateAnimation;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.adapters.LotteryAdapter;
import com.cfcp.a01.common.adapters.LotteryBottomAdapter;
import com.cfcp.a01.common.adapters.LotteryNumDetailsAdapter;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.utils.TimeTools;
import com.cfcp.a01.common.utils.ToastUtils;
import com.cfcp.a01.common.widget.AnKeyboardUtils;
import com.cfcp.a01.common.widget.LotteryNumPop;
import com.cfcp.a01.common.widget.LotteryPlayMethodPop;
import com.cfcp.a01.common.widget.LotteryTypePop;
import com.cfcp.a01.common.widget.PeriodsTipsPop;
import com.cfcp.a01.data.AllGamesResult;
import com.cfcp.a01.data.BetData;
import com.cfcp.a01.data.BetDataResult;
import com.cfcp.a01.data.BetGameSettingsForRefreshResult;
import com.cfcp.a01.data.LogoutResult;
import com.cfcp.a01.data.UpBetData;
import com.cfcp.a01.ui.home.betGenerate.GenerateMoney;
import com.cfcp.a01.ui.home.betGenerate.GenerateNum;
import com.cfcp.a01.ui.home.betGenerate.JointBetNumber;
import com.cfcp.a01.ui.home.sidebar.BackHomeEvent;
import com.cfcp.a01.ui.home.sidebar.LotteryResultEvent;
import com.cfcp.a01.ui.home.sidebar.SideBarFragment;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.kongzue.dialog.v2.CustomDialog;
import com.kongzue.dialog.v2.WaitDialog;
import com.xw.repo.BubbleSeekBar;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.math.BigDecimal;
import java.math.RoundingMode;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Objects;

import butterknife.BindView;
import butterknife.OnClick;
import razerdp.basepopup.BasePopupWindow;
import razerdp.basepopup.QuickPopupBuilder;
import razerdp.basepopup.QuickPopupConfig;

public class BetFragment extends BaseFragment implements BetFragmentContract.View {

    private static final String TYPE = "type";
    private static final String LOTTERY_LIST = "LOTTERY_LIST";
    BetFragmentContract.Presenter presenter;

    @BindView(R.id.betTitleBack)
    TextView betTitleBack;
    @BindView(R.id.betTitleName)
    TextView betTitleName;
    @BindView(R.id.betTitleArrows)
    ImageView betTitleArrows;
    @BindView(R.id.betTitleLay)
    LinearLayout betTitleLay;
    @BindView(R.id.betTitleSet)
    ImageView betTitleSet;
    @BindView(R.id.betTitleMenu)
    ImageView betTitleMenu;
    @BindView(R.id.betArea)
    TextView betArea;
    @BindView(R.id.betChat)
    TextView betChat;
    @BindView(R.id.betMethodName)
    TextView betMethodName;
    @BindView(R.id.betMethodDown)
    ImageView betMethodDown;
    @BindView(R.id.betMethodNameLay)
    LinearLayout betMethodNameLay;
    @BindView(R.id.betIssue)
    TextView betIssue;
    @BindView(R.id.betLastIssue)
    TextView betLastIssue;
    @BindView(R.id.betTime)
    TextView betTime;
    @BindView(R.id.betDaysProfit)
    TextView betDaysProfit;
    @BindView(R.id.betModel)
    TextView betModel;
    @BindView(R.id.betTimes)
    EditText betTimes;
    @BindView(R.id.betMinusTxt)
    TextView betMinusTxt;
    @BindView(R.id.betMinus)
    ImageView betMinus;
    @BindView(R.id.betPlus)
    ImageView betPlus;
    @BindView(R.id.betPlusTxt)
    TextView betPlusTxt;
    @BindView(R.id.betClear)
    ImageView betClear;
    @BindView(R.id.betMoney)
    TextView betMoney;
    @BindView(R.id.betSubmit)
    TextView betSubmit;
    @BindView(R.id.betSure)
    TextView betSure;
    @BindView(R.id.rl_title)
    RelativeLayout rlTitle;
    @BindView(R.id.rv_bet_num)
    RecyclerView rvBetNum;
    @BindView(R.id.rl_info)
    RelativeLayout rlInfo;
    @BindView(R.id.rv_lottery)
    RecyclerView rvLottery;
    @BindView(R.id.kv_lottery)
    KeyboardView kvLottery;
    @BindView(R.id.et_lottery)
    EditText etLottery;
    @BindView(R.id.tv_delete)
    TextView tvDelete;
    @BindView(R.id.tv_clear)
    TextView tvClear;
    @BindView(R.id.ll_lottery_input)
    LinearLayout llLotteryInput;
    @BindView(R.id.rv_top)
    RecyclerView rvTop;
    @BindView(R.id.bs_bet_bar)
    BubbleSeekBar bsBetBar;

    private LotteryTypePop mLotteryTypePop;//彩种选择弹窗
    private LotteryNumPop mLotteryNumPop;//开奖号码弹窗
    private LotteryPlayMethodPop mLotteryPlayingMethodPop;//玩法设置弹窗
    private List<BetGameSettingsForRefreshResult.DataBean.WayGroupsBean> wayGroups;//玩法
    private List<UpBetData> mUpdateBet;//投注号码
    private LotteryAdapter lotteryAdapter;
    private String[] position;//记录玩法选择位置，并且判断是否和上次位置一样
    private AnKeyboardUtils keyboardUtils;
    private GenerateMoney generateMoney;
    private int mListSecSize;//重庆时时彩任选模式下单式玩法选择的位数
    private String mOptional;//重庆时时彩任选模式下单式玩法输入的数字
    private RotateAnimation showArrowAnim;
    private RotateAnimation dismissArrowAnim;
    private AllGamesResult.DataBean.LotteriesBean lotteriesBean;//当前选中的某个彩种数据
    private List<AllGamesResult.DataBean.LotteriesBean> lotteriesBeanList;
    private int lottery_id;
    private String newIssue;//最新的期号
    private Double moneyModel = 1.00;//投注模式
    private int multiple = 1;//倍
    private int number = 0;//注数
    private double onePrice;
    private PeriodsTipsPop periodsTipsPop;
    private CountDownTimer mCountDownTimer;
    //赔率的进度条
    private int mProgressMin;
    private int mProgressMax;
    private int mProgress;
    private float percentRate;
    private float mRate;
    private String seat;//设置任选模式下的任选标记
    private StringBuilder extraPosition = new StringBuilder(); //设置任选模式下任选位数

    private CustomDialog betSuccessTips;//投注成功的提示

    public static BetFragment newInstance(AllGamesResult.DataBean.LotteriesBean lotteriesBean, ArrayList<AllGamesResult.DataBean.LotteriesBean> lotteriesBeanList) {
        BetFragment betFragment = new BetFragment();
        Injections.inject(betFragment, null);
        Bundle args = new Bundle();
        args.putParcelable(TYPE, lotteriesBean);
        args.putParcelableArrayList(LOTTERY_LIST, lotteriesBeanList);
        betFragment.setArguments(args);
        return betFragment;
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            lotteriesBean = getArguments().getParcelable(TYPE);
            lotteriesBeanList = getArguments().getParcelableArrayList(LOTTERY_LIST);
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_bet;
    }

    @SuppressLint("ClickableViewAccessibility")
    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        betTitleName.setText(lotteriesBean.getName());
        lottery_id = lotteriesBean.getId();
        //弹窗的动画效果
        buildShowArrowAnim();
        buildDismissArrowAnim();
        //彩种选择弹窗
        mLotteryTypePop = new LotteryTypePop(_mActivity, lotteriesBeanList, betTitleName.getText().toString());
        mLotteryTypePop.setOnDismissListener(onDismissListener);
        periodsTipsPop = new PeriodsTipsPop(_mActivity);
        betModel.setText("元");
        //获取当前彩种配置信息
        refresh(true);
        //倍数输入的监听
        betTimes.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {

            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }

            @Override
            public void afterTextChanged(Editable s) {
                if (TextUtils.isEmpty(s.toString())) {
                    multiple = 0;
                } else {
                    int maxMultiple = wayGroups.get(Integer.valueOf(position[0])).getChildren().get(Integer.valueOf(position[1])).getChildren().get(Integer.valueOf(position[2])).getMax_multiple();
                    if (maxMultiple != 0 && Integer.valueOf(s.toString()) > maxMultiple) {
                        CharSequence charSequence = String.valueOf(maxMultiple).subSequence(0, String.valueOf(maxMultiple).length());
                        s.replace(0, s.toString().length(), charSequence);
                        ToastUtils.showShortToast("当前所选彩种玩法的最大倍投数为" + maxMultiple + "倍");
                    }
                    multiple = Integer.valueOf(s.toString());
                }
                generateMoney();
            }
        });
    }

    //请求数据
    public void refresh(boolean isRefresh) {
        //当前彩种配置信息
        if (isRefresh) {
            WaitDialog.show(_mActivity, "加载中...").setCanCancel(true);
        }
        presenter.getGameSettingsForRefresh(lottery_id, isRefresh);
    }

    /**
     * 弹窗动画效果
     */
    //弹窗开始动画
    private void buildShowArrowAnim() {
        if (showArrowAnim != null) return;
        showArrowAnim = new RotateAnimation(0, 180f, Animation.RELATIVE_TO_SELF, 0.5f, Animation.RELATIVE_TO_SELF, 0.5f);
        showArrowAnim.setDuration(450);
        showArrowAnim.setInterpolator(new AccelerateDecelerateInterpolator());
        showArrowAnim.setFillAfter(true);
    }

    //弹窗结束动画
    private void buildDismissArrowAnim() {
        if (dismissArrowAnim != null) return;
        dismissArrowAnim = new RotateAnimation(180f, 0f, Animation.RELATIVE_TO_SELF, 0.5f, Animation.RELATIVE_TO_SELF, 0.5f);
        dismissArrowAnim.setDuration(450);
        dismissArrowAnim.setInterpolator(new AccelerateDecelerateInterpolator());
        dismissArrowAnim.setFillAfter(true);
    }

    //箭头开始动画
    private void showArrowAnim(View view) {
        if (view == null) return;
        view.clearAnimation();
        view.startAnimation(showArrowAnim);
    }

    //箭头结束动画
    private void dismissArrowAnim(View view) {
        if (view == null) return;
        view.clearAnimation();
        view.startAnimation(dismissArrowAnim);
    }

    /**
     * 监听弹窗消失
     */
    //标题弹窗消失
    private BasePopupWindow.OnDismissListener onDismissListener = new BasePopupWindow.OnDismissListener() {

        @Override
        public boolean onBeforeDismiss() {
            dismissArrowAnim(betTitleArrows);
            return super.onBeforeDismiss();
        }

        @Override
        public void onDismiss() {
            String title = lotteriesBeanList.get(mLotteryTypePop.getPosition()).getName();
            if (mLotteryTypePop.getPosition() != -1 && !betTitleName.getText().toString().equals(title)) {
                betTitleName.setText(title);
                lottery_id = lotteriesBeanList.get(mLotteryTypePop.getPosition()).getId();
                refresh(true);
            }
        }
    };

    //玩法设置弹窗消失
    private BasePopupWindow.OnDismissListener onDismissListenerMet = new BasePopupWindow.OnDismissListener() {

        @Override
        public boolean onBeforeDismiss() {
            dismissArrowAnim(betMethodDown);
            return super.onBeforeDismiss();
        }

        @Override
        public void onDismiss() {
            mLotteryPlayingMethodPop.refresh();
            if (mLotteryPlayingMethodPop.getConfirm()) {
                if (!Arrays.equals(position, mLotteryPlayingMethodPop.getPosition().split(","))) {
                    position = mLotteryPlayingMethodPop.getPosition().split(",");
                    lotteryAdapter.clearList();
                }
                String mBetMethodDetails = wayGroups.get(Integer.valueOf(position[0])).getChildren().get(Integer.valueOf(position[1])).getChildren().get(Integer.valueOf(position[2])).getName_cn();
                betMethodName.setText(mBetMethodDetails);
                View bottomView = getLayoutInflater().inflate(R.layout.bottom_lottery, (ViewGroup) rvLottery.getParent(), false);
                GenerateNum.generateNum(lottery_id, lotteryAdapter, wayGroups, position, bottomView, rvLottery, llLotteryInput);
                mLotteryPlayingMethodPop.setConfirm(false);
                //输入号码时的监听
                keyboardUtils = new AnKeyboardUtils(_mActivity, kvLottery);
                keyboardUtils.bindEditTextEvent(etLottery);
                etLottery.getText().clear();
                etLottery.addTextChangedListener(new TextWatcher() {
                    @Override
                    public void beforeTextChanged(CharSequence s, int start, int count, int after) {

                    }

                    @Override
                    public void onTextChanged(CharSequence s, int start, int before, int count) {

                    }

                    @SuppressLint("SetTextI18n")
                    @Override
                    public void afterTextChanged(Editable s) {
                        mOptional = s.toString();
                        optional();
                    }
                });
                rvTop.setLayoutManager(new GridLayoutManager(_mActivity, 5));
            }
        }
    };

    @SuppressLint("SetTextI18n")
    @Override
    public void setGameSettingsForRefreshResult(BetGameSettingsForRefreshResult betGameSettingsForRefreshResult, boolean isRefresh) {
        WaitDialog.dismiss();
        if (betGameSettingsForRefreshResult.getErrno() == 0) {
            //历史开奖弹窗、期数、投注时间、最新一期
            setBetNumber(betGameSettingsForRefreshResult);
            //投注区域
            if (isRefresh) {
                //设置默认玩法选择
                wayGroups = betGameSettingsForRefreshResult.getData().getWayGroups();
                betMethodName.setText(wayGroups.get(0).getChildren().get(0).getChildren().get(0).getName_cn());
                position = new String[]{"0", "0", "0"};
                rvLottery.setVisibility(View.VISIBLE);
                llLotteryInput.setVisibility(View.GONE);
                rvLottery.setLayoutManager(new LinearLayoutManager(_mActivity));
                ((SimpleItemAnimator) Objects.requireNonNull(rvLottery.getItemAnimator())).setSupportsChangeAnimations(false);
                if (lotteryAdapter != null) {
                    lotteryAdapter.clearList();
                } else {
                    lotteryAdapter = new LotteryAdapter();
                    rvLottery.setAdapter(lotteryAdapter);
                }
                mLotteryPlayingMethodPop = new LotteryPlayMethodPop(_mActivity, wayGroups);
                mLotteryPlayingMethodPop.setOnDismissListener(onDismissListenerMet);
                GenerateNum.generateNum(lotteryAdapter, lottery_id);
                //赔率设定
                mProgressMin = Integer.valueOf(betGameSettingsForRefreshResult.getData().getOptionalPrizes().get(0).getPrize_group());
                mProgressMax = Integer.valueOf(betGameSettingsForRefreshResult.getData().getOptionalPrizes().get(betGameSettingsForRefreshResult.getData().getOptionalPrizes().size() - 1).getPrize_group());
                bsBetBar.getConfigBuilder().min(mProgressMin).max(mProgressMax).build();
                bsBetBar.setProgress(bsBetBar.getMax());
                mProgress = bsBetBar.getProgress();
                betMinusTxt.setText("0.0%");
                betPlusTxt.setText(String.valueOf(mProgress));
                mRate = 100 * Float.valueOf(betGameSettingsForRefreshResult.getData().getOptionalPrizes().get(0).getRate()) / (mProgressMax - mProgressMin);
                bsBetBar.setOnProgressChangedListener(new BubbleSeekBar.OnProgressChangedListener() {
                    @Override
                    public void onProgressChanged(BubbleSeekBar bubbleSeekBar, int progress, float progressFloat, boolean fromUser) {
                        mProgress = progress;
                        percentRate = mRate * (mProgressMax - progress);
                        BigDecimal bg = new BigDecimal(percentRate);
                        double percent = bg.setScale(2, BigDecimal.ROUND_HALF_UP).doubleValue();
                        betMinusTxt.setText(percent + "%");
                        betPlusTxt.setText(String.valueOf(progress));
                    }

                    @Override
                    public void getProgressOnActionUp(BubbleSeekBar bubbleSeekBar, int progress, float progressFloat) {

                    }

                    @Override
                    public void getProgressOnFinally(BubbleSeekBar bubbleSeekBar, int progress, float progressFloat, boolean fromUser) {

                    }
                });
            }
        } else {
            ToastUtils.showShortToast(betGameSettingsForRefreshResult.getError());
            finish();
        }
    }

    //投注结果
    @Override
    public void setBetResult(final BetDataResult betDataResult) {
        WaitDialog.dismiss();
        if (betDataResult.getErrno() == 0) {
            lotteryAdapter.clearList();
            etLottery.getText().clear();
            betSuccessTips = CustomDialog.show(_mActivity, R.layout.layout_bet_success_tips, new CustomDialog.BindView() {
                @Override
                public void onBind(CustomDialog dialog, View rootView) {
                    rootView.findViewById(R.id.iv_close).setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View v) {
                            betSuccessTips.doDismiss();
                        }
                    });
                }
            });
        } else {
            betSuccessTips = CustomDialog.show(_mActivity, R.layout.layout_bet_success_tips, new CustomDialog.BindView() {
                @Override
                public void onBind(CustomDialog dialog, View rootView) {
                    rootView.findViewById(R.id.iv_close).setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View v) {
                            betSuccessTips.doDismiss();
                        }
                    });
                    TextView tvTips = rootView.findViewById(R.id.tv_tips);
                    ImageView ivTips = rootView.findViewById(R.id.iv_tips);
                    tvTips.setText(betDataResult.getError());
                    ivTips.setImageResource(R.mipmap.ic_bet_failure_tips);
                }
            });
        }
    }

    @Override
    public void setPresenter(BetFragmentContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Subscribe
    public void onEventMain(LotteryResultEvent lotteryResultEvent) {
        GameLog.log("======LotteryResultEvent==========投注页面需 要消失的================");
        finish();
    }

    @Subscribe
    public void onEventMain(BackHomeEvent backHomeEvent) {
        GameLog.log("=====BackHomeEvent===========投注页面需要消失的================");
        finish();
    }

    @Subscribe
    public void onEventMain(LogoutResult logoutResult) {
        GameLog.log("================投注页面需要消失的================");
        finish();
    }

    //非任选且不为单式时刷新投注注数及金额
    @Subscribe
    public void onEventMain(List<UpBetData> updateBet) {
        mUpdateBet = updateBet;
        generateMoney = new GenerateMoney(lottery_id, wayGroups, position, mUpdateBet);
        number = generateMoney.generateMoney();
        generateMoney();
    }

    //任选且为单式时刷新投注注数及金额
    @Subscribe
    public void onEventMain(OptionalSizeEvent optionalSizeEvent) {
        mListSecSize = optionalSizeEvent.getSize();
        optional();
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        if (mCountDownTimer != null) {
            mCountDownTimer.cancel();
        }
        EventBus.getDefault().unregister(this);
    }

    @SuppressLint("SetTextI18n")
    @OnClick({R.id.betTitleBack, R.id.betTitleLay, R.id.betTitleSet, R.id.betTitleMenu, R.id.betArea, R.id.betChat, R.id.betMethodNameLay, R.id.betModel, R.id.betMinus, R.id.betPlus, R.id.betClear, R.id.betSubmit, R.id.betSure, R.id.tv_delete, R.id.tv_clear})
    public void onViewClicked(View view) {
        //赔率的进度条  减法不能小于最小值
        switch (view.getId()) {
            case R.id.betTitleBack:
                finish();
                break;
            case R.id.betTitleLay:
                showArrowAnim(betTitleArrows);
                mLotteryTypePop.showPopupWindow(rlTitle);
                break;
            case R.id.betTitleSet:
                Animation enterAnimation = createVerticalAnimation(-1f, 0);
                Animation dismissAnimation = createVerticalAnimation(0, -1f);
                int gravity = Gravity.BOTTOM | Gravity.CENTER_HORIZONTAL;
                QuickPopupBuilder.with(getContext())
                        .contentView(R.layout.pop_lottery_info)
                        .config(new QuickPopupConfig()
                                .clipChildren(true)
                                .withShowAnimation(enterAnimation)
                                .withDismissAnimation(dismissAnimation)
                                .gravity(gravity))
                        .show(betTitleSet);
                break;
            case R.id.betTitleMenu:
                SideBarFragment.newInstance().show(getFragmentManager());
                break;
            case R.id.betArea:
                break;
            case R.id.betChat:
                break;
            case R.id.betMethodNameLay:
                showArrowAnim(betMethodDown);
                mLotteryPlayingMethodPop.showPopupWindow(rlInfo);
                break;
            case R.id.betModel:
                enterAnimation = createVerticalAnimation(1f, 0);
                dismissAnimation = createVerticalAnimation(0, 1f);
                gravity = Gravity.TOP | Gravity.CENTER_HORIZONTAL;
                QuickPopupBuilder.with(getContext())
                        .contentView(R.layout.pop_lottery_betmodel)
                        .config(new QuickPopupConfig()
                                .clipChildren(true)
                                .withShowAnimation(enterAnimation)
                                .withDismissAnimation(dismissAnimation)
                                .gravity(gravity)
                                .withClick(R.id.tv_yuan, new View.OnClickListener() {
                                    @Override
                                    public void onClick(View v) {
                                        moneyModel = 1.00;
                                        betModel.setText("元");
                                        generateMoney();
                                    }
                                }, true)
                                .withClick(R.id.tv_jiao, new View.OnClickListener() {
                                    @Override
                                    public void onClick(View v) {
                                        moneyModel = 0.10;
                                        betModel.setText("角");
                                        generateMoney();
                                    }
                                }, true))
                        .show(betModel);
                break;
            case R.id.betMinus:
                mProgress--;
                if (mProgress <= mProgressMin) {
                    mProgress = mProgressMin;
                }
                setRate(mProgress);
                break;
            case R.id.betPlus:
                mProgress++;
                if (mProgress >= mProgressMax) {
                    mProgress = mProgressMax;
                }
                setRate(mProgress);
                break;
            case R.id.betClear:
                lotteryAdapter.clearList();
                etLottery.getText().clear();
                break;
            case R.id.betSubmit:
                WaitDialog.show(_mActivity, "提交中...").setCanCancel(true);
                BetData betData = new BetData();
                betData.setGameId(lottery_id);
                betData.setTraceStopValue(1);
                betData.setIsTrace(0);
                Map<String, Integer> mapOrders = new HashMap<>();
                mapOrders.put(newIssue, multiple);
                betData.setOrders(JSON.toJSON(mapOrders));
                List<BetData.BallsBean> list = new ArrayList<>();
                BetData.BallsBean ballsBean = new BetData.BallsBean();
                ballsBean.setJsId(0);
                ballsBean.setMultiple(multiple);
                ballsBean.setMoneyunit(moneyModel * 0.5);
                ballsBean.setBall(JointBetNumber.jointNum(mUpdateBet, lottery_id, wayGroups, position));
                ballsBean.setWayId(wayGroups.get(Integer.valueOf(position[0])).getChildren().get(Integer.valueOf(position[1])).getChildren().get(Integer.valueOf(position[2])).getSeries_way_id());
                ballsBean.setNum(number);
                ballsBean.setViewBalls("");
                ballsBean.setType(wayGroups.get(Integer.valueOf(position[0])).getName_en() + "." + wayGroups.get(Integer.valueOf(position[0])).getChildren().get(Integer.valueOf(position[1])).getName_en() + "." + wayGroups.get(Integer.valueOf(position[0])).getChildren().get(Integer.valueOf(position[1])).getChildren().get(Integer.valueOf(position[2])).getName_en());
                ballsBean.setPrizeGroup(mProgress);
                Map<String, String> mapExtra = new HashMap<>();
                if (mUpdateBet.get(0).getListSec().size() != 0) {
                    setExtraParameter();
                    mapExtra.put("position", extraPosition.toString());
                    mapExtra.put("seat", seat);
                }
                ballsBean.setExtra(JSON.toJSON(mapExtra));
                list.add(ballsBean);
                betData.setBalls(list);
                betData.setAmount(onePrice);
                betData.setTraceWinStop(1);
                String betJson = JSON.toJSONString(betData);
                Log.e("colin---betJson", betJson);
                presenter.getBet(lottery_id, betJson);
                break;
            case R.id.betSure:
                break;
            case R.id.tv_delete:
                if (!TextUtils.isEmpty(etLottery.getText().toString())) {
                    etLottery.setText(generateMoney.setPopup(_mActivity).toString().replace("[", "").replace("]", ""));
                    etLottery.setSelection(etLottery.getText().length());
                }
                break;
            case R.id.tv_clear:
                etLottery.getText().clear();
                break;
        }
    }

    private void setExtraParameter() {
        int detailId = wayGroups.get(Integer.valueOf(position[0])).getChildren().get(Integer.valueOf(position[1])).getChildren().get(Integer.valueOf(position[2])).getId();
        if (lottery_id == 1 || lottery_id == 13 || lottery_id == 16) {//重庆时时彩任选模式下的投注参数
            if (wayGroups.get(Integer.valueOf(position[0])).getId() == 93) {
                //任选直选模式
                if (detailId == 199 || detailId == 179 || detailId == 180) {
                    for (int i = 0; i < mUpdateBet.size(); i++) {
                        if (mUpdateBet.get(i).getSelectList().size() != 0) {
                            extraPosition.append(i);
                        }
                    }
                } else {//其他模式
                    extraPosition.append(mUpdateBet.get(0).getListSec().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", ""));
                }
                //任选标识
                switch (wayGroups.get(Integer.valueOf(position[0])).getChildren().get(Integer.valueOf(position[1])).getId()) {
                    case 94:
                        seat = "2";
                        break;
                    case 95:
                        seat = "3";
                        break;
                    case 96:
                        seat = "4";
                        break;
                }
            }
        }
    }

    //设置开奖号码、当前期数、最新期数以及倒计时
    @SuppressLint("SetTextI18n")
    private void setBetNumber(BetGameSettingsForRefreshResult betGameSettingsForRefreshResult) {
        LinearLayoutManager betNum = new LinearLayoutManager(_mActivity);
        betNum.setOrientation(LinearLayoutManager.HORIZONTAL);
        rvBetNum.setLayoutManager(betNum);
        LotteryNumDetailsAdapter lotteryNumAdapter;
        List<String> numList = new ArrayList<>();
        if (TextUtils.isEmpty(betGameSettingsForRefreshResult.getData().getIssueHistory().getIssues().get(0).getWn_number())) {
            numList.add("开奖中...");
            lotteryNumAdapter = new LotteryNumDetailsAdapter(R.layout.item_lottery_details_txt, numList);
            new CountDownTimer(5 * 1000, 1000) {
                @Override
                public void onTick(long millisUntilFinished) {
                }

                public void onFinish() {
                    refresh(false);
                }
            }.start();
        } else {
            numList.addAll(Arrays.asList(betGameSettingsForRefreshResult.getData().getIssueHistory().getIssues().get(0).getWn_number().split(",")));
            if (numList.size() > 5) {
                numList.clear();
                numList.add("点击查看开奖结果");
                lotteryNumAdapter = new LotteryNumDetailsAdapter(R.layout.item_lottery_details_txt, numList);
            } else {
                lotteryNumAdapter = new LotteryNumDetailsAdapter(R.layout.item_lottery_num_details, numList);
            }
        }
        rvBetNum.setAdapter(lotteryNumAdapter);
        mLotteryNumPop = new LotteryNumPop(_mActivity, betGameSettingsForRefreshResult.getData().getIssueHistory().getIssues());
        lotteryNumAdapter.setOnItemClickListener(new BaseQuickAdapter.OnItemClickListener() {
            @Override
            public void onItemClick(BaseQuickAdapter adapter, View view, int position) {
                mLotteryNumPop.showPopupWindow(rlInfo);
            }
        });
        betIssue.setText("第" + betGameSettingsForRefreshResult.getData().getIssueHistory().getIssues().get(0).getIssue() + "期");
        betLastIssue.setText("第" + betGameSettingsForRefreshResult.getData().getIssueHistory().getCurrent_issue() + "期");
        newIssue = betGameSettingsForRefreshResult.getData().getGameNumbers().get(1).getNumber();
        long millisFuture = (long) betGameSettingsForRefreshResult.getData().getCurrentNumberTime() * 1000 - (long) betGameSettingsForRefreshResult.getData().getCurrentTime() * 1000;
        if (millisFuture != 0) {
            initCountDownTimer(millisFuture);
        }
    }

    //设置赔率
    @SuppressLint("SetTextI18n")
    private void setRate(int progress) {
        percentRate = mRate * (mProgressMax - progress);
        BigDecimal bg = new BigDecimal(percentRate);
        double percent = bg.setScale(2, BigDecimal.ROUND_HALF_UP).doubleValue();
        bsBetBar.setProgress(mProgress);
        betMinusTxt.setText(percent + "%");
        betPlusTxt.setText(String.valueOf(progress));
    }

    //监听开奖倒计时
    private void initCountDownTimer(long millisInFuture) {
        if (mCountDownTimer != null) {
            mCountDownTimer.cancel();
        }
        mCountDownTimer = new CountDownTimer(millisInFuture, 1000) {
            @Override
            public void onTick(long millisUntilFinished) {
                if (betTime != null) {
                    betTime.setText(TimeTools.getCountTimeByLong(millisUntilFinished));
                }
            }

            public void onFinish() {
                if (!periodsTipsPop.isShowing()) {
                    periodsTipsPop.setPeriods(newIssue);
                    periodsTipsPop.showPopupWindow();
                    refresh(false);
                }
            }
        }.start();
    }

    //任选模式下单式的计算注数
    private void optional() {
        generateMoney = new GenerateMoney(lottery_id, wayGroups, position, mOptional, mListSecSize);
        number = generateMoney.generateMoney();
        generateMoney();
    }

    //计算注数以及投注金额
    @SuppressLint("SetTextI18n")
    private void generateMoney() {
        if (number * multiple * moneyModel == 0) {
            betSubmit.setClickable(false);
            betSubmit.setBackgroundResource(R.drawable.btn_bet_submit_no);
            betMoney.setText(number + "注0元");
        } else {
            betSubmit.setClickable(true);
            betSubmit.setBackgroundResource(R.drawable.btn_bet_submit);
            BigDecimal bg = new BigDecimal(number * multiple * moneyModel).setScale(2, RoundingMode.DOWN);
            onePrice = bg.doubleValue();
            betMoney.setText(number + "注" + onePrice + "元");
        }
    }

    //返回键的监听
    @Override
    public boolean onBackPressedSupport() {
        if (keyboardUtils != null && keyboardUtils.isShow()) {
            keyboardUtils.hideKeyBoard();
            return true;
        } else {
            return super.onBackPressedSupport();
        }
    }

    //模式选择的弹窗动画
    private Animation createVerticalAnimation(float fromY, float toY) {
        Animation animation = new TranslateAnimation(Animation.RELATIVE_TO_SELF,
                0f,
                Animation.RELATIVE_TO_SELF,
                0f,
                Animation.RELATIVE_TO_SELF,
                fromY,
                Animation.RELATIVE_TO_SELF,
                toY);
        animation.setDuration(500);
        animation.setInterpolator(new DecelerateInterpolator());
        return animation;
    }
}