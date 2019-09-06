package com.gmcp.gm.ui.me.userlist;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.UserListResult;

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
