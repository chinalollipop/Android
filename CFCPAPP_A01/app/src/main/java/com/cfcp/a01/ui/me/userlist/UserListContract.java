package com.cfcp.a01.ui.me.userlist;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.UserListResult;

import java.util.List;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface UserListContract {

    interface Presenter extends IPresenter {

        void getUserList(String parent_id, String end_date);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getUserListResult(List<UserListResult> userListResult);
    }
}
