package com.hfcp.hf.ui.me.userlist;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.UserListResult;

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
