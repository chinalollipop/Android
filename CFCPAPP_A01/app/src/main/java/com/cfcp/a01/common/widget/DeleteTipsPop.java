package com.cfcp.a01.common.widget;

import android.annotation.SuppressLint;
import android.content.Context;
import android.text.TextUtils;
import android.view.View;
import android.view.animation.Animation;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.cfcp.a01.R;

import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import razerdp.basepopup.BasePopupWindow;

/**
 * Created by Colin on 2019/3/1.
 * 删除错误号码的弹窗提醒
 */
public class DeleteTipsPop extends BasePopupWindow {


    @BindView(R.id.tv_tips)
    TextView tvTips;
    @BindView(R.id.tv_content)
    TextView tvContent;
    @BindView(R.id.ll_content)
    LinearLayout llContent;
    @BindView(R.id.tv_none)
    TextView tvNone;
    @BindView(R.id.tv_confirm)
    TextView tvConfirm;

    @SuppressLint("SetTextI18n")
    public DeleteTipsPop(Context context, List<String> diff, String interception) {
        super(context);
        StringBuilder stringBuilder = new StringBuilder();
        if (!TextUtils.isEmpty(interception)) {
            for (int i = 0; i < interception.length(); i++) {
                if (i != interception.length() - 1) {
                    stringBuilder.append(interception.charAt(i)).append(",");
                } else {
                    stringBuilder.append(interception.charAt(i));
                }
            }
        }

        if (diff.size() == 0 && interception.length() == 0) {
            llContent.setVisibility(View.GONE);
            tvNone.setVisibility(View.VISIBLE);
        } else {
            llContent.setVisibility(View.VISIBLE);
            tvNone.setVisibility(View.GONE);
            if (diff.size() == 0) {
                tvTips.setText(interception.length() + "个无效数字");
                tvContent.setText(stringBuilder.toString());
            } else {
                if (TextUtils.isEmpty(interception)) {
                    tvTips.setText(diff.size() + "个无效号码");
                    tvContent.setText(diff.toString().replace("[", "").replace("]", ""));
                } else {
                    tvTips.setText(diff.size() + "个无效号码，" + interception.length() + "个无效数字");
                    tvContent.setText(diff.toString().replace("[", "").replace("]", "") + " + " + stringBuilder.toString());
                }
            }
        }

        tvConfirm.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dismiss();
            }
        });
    }

    @Override
    protected Animation onCreateShowAnimation() {
        return getDefaultScaleAnimation();
    }

    @Override
    protected Animation onCreateDismissAnimation() {
        return getDefaultScaleAnimation(false);
    }

    @Override
    public View onCreateContentView() {
        View view = createPopupById(R.layout.pop_delete_tips);
        ButterKnife.bind(this, view);
        return view;
    }
}
