package com.hgapp.betnhg.common.util;


import android.support.annotation.StringRes;

import com.hgapp.betnhg.interfaces.ResourceGetter;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.hgapp.common.util.RegexUtils;
import com.hgapp.common.util.Timber;

import java.math.BigDecimal;
import java.util.regex.Pattern;

/**
 * Created by Daniel on 2017/4/25.
 */

public class HGCheck {
    public static class CheckResult {
        public boolean isResultOk;
        public String msg;
        public String extra_msg;

        public CheckResult() {
        }

        public CheckResult(boolean isResultOk) {
            this.isResultOk = isResultOk;
        }

        @Override
        public String toString() {
            return "CheckResult{" +
                    "isResultOk=" + isResultOk +
                    ", msg='" + msg + '\'' +
                    ", extra_msg='" + extra_msg + '\'' +
                    '}';
        }
    }

    private static ResourceGetter resourceGetter;

    private HGCheck() {
    }

    public static void setResourceGetter(ResourceGetter resGetter) {
        resourceGetter = resGetter;
    }

    /**
     * 收集检查结果，如果任何一个检查结果为错，则返回错误
     *
     * @param results
     * @return
     */
    public static CheckResult collect(CheckResult... results) {
        StringBuilder builder = new StringBuilder();
        CheckResult collectResult = null;
        for (CheckResult result : results) {
            if (!result.isResultOk) {
                collectResult = result;
                builder.append(result.msg);
                builder.append("|||");
            }
        }
        if (null != collectResult) {
            collectResult.extra_msg = builder.toString();
            GameLog.log(collectResult.toString());
            return collectResult;
        }

        return new CheckResult(true);
    }

    /**
     * 检查一致
     *
     * @param first
     * @param second
     * @param strid
     * @return
     */
    public static CheckResult checkSame(CharSequence first, CharSequence second, @StringRes int strid) {
        CheckResult checkResult = new CheckResult(false);
        if (null == first || null == second || !first.equals(second)) {
            checkResult.msg = resourceGetter.getString(strid);
            return checkResult;
        }
        return new CheckResult(true);
    }

    /**
     * 检查不为空
     *
     * @param input
     * @param stringid
     * @return
     */
    public static CheckResult checkNotEmpty(CharSequence input, @StringRes int stringid) {
        if (Check.isEmpty(input)) {
            CheckResult checkResult = new CheckResult(false);
            checkResult.msg = resourceGetter.getString(stringid);
            return checkResult;
        }
        return new CheckResult(true);
    }

    public static CheckResult checkNotEmpty(CharSequence input) {
        if (Check.isEmpty(input)) {
            CheckResult checkResult = new CheckResult(false);
            return checkResult;
        }
        return new CheckResult(true);
    }

    public static CheckResult checkSmsCode(String smscode) {
        CheckResult checkResult = new CheckResult(false);
        if (Check.isEmpty(smscode)) {
            //checkResult.msg = resourceGetter.getString(R.string.str_empty_smscode);
            return checkResult;
        }

        if (smscode.length() != 6) {
            //checkResult.msg = resourceGetter.getString(R.string.str_validlength_of_smscode);
            return checkResult;
        }

        for (char achar : smscode.toCharArray()) {
            if (!Character.isDigit(achar)) {
                //checkResult.msg = resourceGetter.getString(R.string.str_number_only_smscode);
                return checkResult;
            }
        }
        checkResult.isResultOk = true;
        return checkResult;
    }

    /**
     * 推荐码由6位数字组成,没有可不填
     *
     * @param code
     * @return
     */
    public static CheckResult checkRecommandCode(String code) {
        CheckResult checkResult = new CheckResult(false);
        if (!Check.isEmpty(code)) {
            if (code.length() != 6) {
                checkResult.msg = "推荐码由6位数字组成";
                return checkResult;
            }

            for (char achar : code.toCharArray()) {
                if (!Character.isDigit(achar)) {
                    checkResult.msg = "推荐码由纯数字组成";
                    return checkResult;
                }
            }
        }

        checkResult.isResultOk = true;
        return checkResult;
    }

    /**
     * 检查手机号是否合法
     *
     * @param number
     * @return
     */
    public static CheckResult checkPhoneNumber(String number, @StringRes int emptyStringId, @StringRes int invalidStringId) {
        CheckResult checkResult = new CheckResult(false);
        if (Check.isEmpty(number)) {
            checkResult.msg = resourceGetter.getString(emptyStringId);
            return checkResult;
        }
        if (!RegexUtils.isMobileExact(number)) {
            checkResult.msg = resourceGetter.getString(invalidStringId);
            return checkResult;
        }

        checkResult.isResultOk = true;
        return checkResult;
    }

    /**
     * 检查手机号是否合法
     *
     * @param number
     * @return
     */
    public static CheckResult checkPhoneNumber(String number) {
        CheckResult checkResult = new CheckResult(false);
        if (Check.isEmpty(number)) {
            //checkResult.msg = resourceGetter.getString(R.string.str_phone_empty);
            return checkResult;
        }

        /*if (!RegexUtils.isMobileExact(number)) {
            checkResult.msg = resourceGetter.getString(R.string.str_phone_invalid);
            return checkResult;
        }*/
        String regex = "^1[3-9]\\d{9,10}$";
        if(!Pattern.matches(regex,number))
        {
            //checkResult.msg = resourceGetter.getString(R.string.str_phone_invalid);
            return checkResult;
        }

        checkResult.isResultOk = true;
        return checkResult;
    }

    /**
     * 检查动态码是否合法
     *
     * @param verifycode
     * @return
     */
    public static CheckResult checkVerifyCode(String verifycode) {
        CheckResult checkResult = new CheckResult(false);
        if (Check.isEmpty(verifycode)) {
            checkResult.msg = "验证码不能为空";
            return checkResult;
        }

        if (verifycode.length() != 4) {
            checkResult.msg = "验证码必须为4位数";
            return checkResult;
        }

        for (char achar : verifycode.toCharArray()) {
            if (!Character.isDigit(achar)) {
                checkResult.msg = "验证码必须为纯数字";
                return checkResult;
            }
        }
        checkResult.isResultOk = true;
        return checkResult;
    }

    /**
     * 检查用户名是否合法
     *
     * @param username
     * @return
     */
    public static CheckResult checkUsername(String username) {
        CheckResult checkResult = new CheckResult(false);
        if (Check.isEmpty(username)) {
            //checkResult.msg = resourceGetter.getString(R.string.str_username_empty);
            return checkResult;
        }
        Pattern pattern = null;
        pattern = Pattern.compile("((^[a|A][g|G][a-zA-Z0-9]{4,18}$)|(^1[3|4|5|7|8][0-9]{9}$))");

        if (!pattern.matcher(username).matches()) {
            //checkResult.msg = resourceGetter.getString(R.string.str_username_invalid);
            return checkResult;
        }
        return new CheckResult(true);
    }

    /**
     * 检查游戏密码
     *
     * @param password
     * @param emptystrid
     * @param invalidstrid
     * @return
     */
    public static CheckResult checkPassword(String password, @StringRes int emptystrid, @StringRes int invalidstrid) {
        CheckResult checkResult = new CheckResult(false);
        if (Check.isEmpty(password)) {
            checkResult.msg = resourceGetter.getString(emptystrid);
            return checkResult;
        }
        Pattern pattern = Pattern.compile("[a-zA-Z0-9]{8,10}");
        if (!pattern.matcher(password).matches()) {
            checkResult.msg = resourceGetter.getString(invalidstrid);
            return checkResult;
        }
        return new CheckResult(true);
    }
    /**
     * 检查游戏密码
     *
     * @param password
     * @param emptystrid
     * @param invalidstrid
     * @return
     */
    public static CheckResult checkNWFPassword(String password, @StringRes int emptystrid, @StringRes int invalidstrid) {
        CheckResult checkResult = new CheckResult(false);
        if (Check.isEmpty(password)) {
            checkResult.msg = resourceGetter.getString(emptystrid);
            return checkResult;
        }
        Pattern pattern = Pattern.compile("[a-zA-Z0-9]{6,16}");
        if (!pattern.matcher(password).matches()) {
            checkResult.msg = resourceGetter.getString(invalidstrid);
            return checkResult;
        }
        return new CheckResult(true);
    }
    /**
     * Deprecated已经废弃
     * 检查密码是否合法
     *
     * @param password
     * @return
     */
    @Deprecated
    public static CheckResult checkPassword(String password) {
        return checkAccount("密码", password);
    }


    private static CheckResult checkAccount(String hearder, String account) {
        CheckResult result = new CheckResult();
        result.isResultOk = false;
        if (Check.isEmpty(account)) {
            result.msg = hearder + "不能为空";
            return result;
        }

        if (account.length() < 6 || account.length() > 10) {
            result.msg = hearder + "由6-10位小写字母或数字组成";
            return result;
        }
        for (char achar : account.toCharArray()) {
            if (!(Character.isDigit(achar)
                    || (Character.isLetter(achar) && Character.isLowerCase(achar)))) {
                result.msg = hearder + "必须由小写字母或数字组成";
                return result;
            }
        }

        result.isResultOk = true;
        return result;
    }

    /**
     * 检查备注
     *
     * @param charSequence
     * @return
     */
    public static CheckResult checkRemark(CharSequence charSequence) {
        CheckResult checkResult = new CheckResult(false);

        if (!Check.isEmpty(charSequence)) {
            String remark = charSequence.toString();
            /*if(!RegexUtils.isZh(remark))
            {
                checkResult.msg = resourceGetter.getString(R.string.)
                return checkResult;
            }*/
            if (remark.length() > 22) {
                //checkResult.msg = resourceGetter.getString(R.string.str_withdraw_remark_toolong);
                return checkResult;
            }
        }
        return new CheckResult(true);
    }

    /**
     * 检查取款金额
     *
     * @param charSequence
     * @return
     */
    public static CheckResult checkWithdrawMoney(CharSequence charSequence,String balance) {
        if(Check.isEmpty(balance))
        {
            CheckResult checkResult = new CheckResult(false);
            //checkResult.msg = ResHelper.getString(R.string.str_balance_not_sufficient);
            return checkResult;
        }
        Pattern pattern = Pattern.compile("^[0-9]+$|^[0-9]+\\.[0-9]{1,2}$");

        CheckResult checkResult = new CheckResult(false);

        if (Check.isEmpty(charSequence.toString())) {
            //checkResult.msg = resourceGetter.getString(R.string.str_withdraw_no_money_input);
            return checkResult;
        }

        if(!pattern.matcher(charSequence.toString()).matches())
        {
            //checkResult.msg = resourceGetter.getString(R.string.str_withdraw_money_format_error);
            return checkResult;
        }

        try {
            BigDecimal bigDecimalBalance = new BigDecimal(balance);
            BigDecimal ONE_HUNDRED = new BigDecimal("10");
            if(bigDecimalBalance.compareTo(ONE_HUNDRED) < 0)
            {
                //checkResult.msg = resourceGetter.getString(R.string.str_withdraw_money_balance_toosmall);
                return checkResult;
            }

            BigDecimal maxMoney  = new BigDecimal("5000000");
            BigDecimal money = new BigDecimal(charSequence.toString());
            if (money.compareTo(ONE_HUNDRED) < 0 || money.compareTo(maxMoney) > 0) {
                //checkResult.msg = resourceGetter.getString(R.string.str_withdraw_money_must_above_100);
                return checkResult;
            }


            if(money.compareTo(bigDecimalBalance) >0 )
            {
                //checkResult.msg = resourceGetter.getString(R.string.str_withdraw_money_balance_insufficient);
                return checkResult;
            }
        } catch (NumberFormatException ex) {
            Timber.e(ex,"取款金额转换为BigDecimal的时候出错");
            //checkResult.msg = resourceGetter.getString(R.string.str_withdraw_money_invalid);
            return checkResult;
        }
        catch (Exception e)
        {
            Timber.e(e,"校验取款金额的时候出错");
            //checkResult.msg = resourceGetter.getString(R.string.str_withdraw_money_invalid);
            return checkResult;
        }

        return new CheckResult(true);
    }

    /**
     * 检查额度转移的金额
     *
     * @param money
     * @param balance
     * @return
     */
    public static CheckResult checkFundShiftMoney(String money, String  balance) {
        Pattern pattern = Pattern.compile("^[0-9]+$|^[0-9]+\\.[0-9]{1,2}$");
        CheckResult checkResult = new CheckResult(false);
        if (Check.isEmpty(money)) {
            checkResult.msg = "";
            return checkResult;
        }

        if (!pattern.matcher(money).matches()) {
            //checkResult.msg = resourceGetter.getString(R.string.str_fundshift_money_invalid);
            return checkResult;
        }

        BigDecimal dMoney = null;
        try {
            dMoney = new BigDecimal(money);
        } catch (Exception e) {

        }
        BigDecimal ONE = new BigDecimal("1");
        if (dMoney.compareTo(ONE) < 0) {
            //checkResult.msg = resourceGetter.getString(R.string.str_fundshift_money_less);
            return checkResult;
        }

        BigDecimal bdBalance = new BigDecimal(balance);
        if (dMoney.compareTo(bdBalance) > 0) {
            //checkResult.msg = ResHelper.getString(R.string.str_game_balance_not_enough);
            return checkResult;
        }

        return new CheckResult(true);
    }

    /**
     * 检查银行账户名称
     *
     * @return
     */
    public static CheckResult checkAccountName(CharSequence input) {
        CheckResult checkResult = new CheckResult(false);
        if (Check.isEmpty(input)) {
            //checkResult.msg = resourceGetter.getString(R.string.str_addbank_empty_account_name);
            return checkResult;
        }
        if (input.length() > 20) {
            //checkResult.msg = resourceGetter.getString(R.string.str_addbank_account_name_toolong);
            return checkResult;
        }
        Pattern pattern = Pattern.compile("[\u4e00-\u9fa5_a-zA-Z.·•▪·.]{2,20}");
        if (!pattern.matcher(input).matches()) {
            //checkResult.msg = resourceGetter.getString(R.string.str_addbank_invalid_account_name);
            return checkResult;
        }

        return new CheckResult(true);
    }

    /**
     * 检查银行账号
     *
     * @param input
     * @return
     */
    public static CheckResult checkAccountNO(CharSequence input) {
        CheckResult checkResult = new CheckResult(false);
        if (Check.isEmpty(input)) {
            //checkResult.msg = resourceGetter.getString(R.string.str_addbank_account_no_empty);
            return checkResult;
        }
        if (input.length() > 19) {
            //checkResult.msg = resourceGetter.getString(R.string.str_addbank_account_no_toolong);
            return checkResult;
        }

        Pattern pattern = Pattern.compile("^\\d{16,19}$");
        if (!pattern.matcher(input).matches()) {
            //checkResult.msg = resourceGetter.getString(R.string.str_addbank_account_no_invalid);
            return checkResult;
        }

        return new CheckResult(true);
    }

    /**
     * 检查开户网点
     *
     * @param sequence
     * @return
     */
    public static CheckResult checkAccountSite(CharSequence sequence) {
        CheckResult checkResult = new CheckResult(false);
        if (Check.isEmpty(sequence)) {
            //checkResult.msg = resourceGetter.getString(R.string.str_addbank_account_site_empty);
            return checkResult;
        }

        if (sequence.length() > 20) {
            //checkResult.msg = resourceGetter.getString(R.string.str_addbank_account_site_toolong);
            return checkResult;
        }

        Pattern pattern = Pattern.compile("^[\u4e00-\u9fa5_a-zA-Z]{2,20}$");
        if (!pattern.matcher(sequence).matches()) {
            //checkResult.msg = resourceGetter.getString(R.string.str_addbank_account_site_invalid);
            return checkResult;
        }
        return new CheckResult(true);
    }
}
