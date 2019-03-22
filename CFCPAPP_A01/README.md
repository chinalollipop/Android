注意事项：  

因为是联合开发，各自各自的编码风格和习惯，请遵循以下几点做为开发前提，也方便代码阅读  

  
1、所有的目录结构请不要修改   

2、实体类请在com.cfcp.a01.data包名下添加即可，   
    编写规则 ，一律以Result为结尾的实体类   
    为了防止相互重复，请你编写实体类的时候添加一个C开头的，我以D开头 的。  
    如历史奖期实体类 CLotteryResult   
    
2、请在com.cfcp.a01.ui.home.bet.BetFragment类里面直接编写所有下注的逻辑和接口即可  

3、common包含了大多数用到的工具类。  

	如果需要，请再common找那个新建包名，跟其他的区分开来  
	
4、自定义视图类，请在common包名下的widget中添加  

5、工具类，请放在common包名下utils包中  

6、所有的新增加的界面请以Fragment来做为视图，不要写Acitivity  
	启动视图已StartBrotherEvent来启动 ，已finish来关闭  
	如启动登录界面  
	EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));  
	
7、代码框架请已MVP的结构来写，详情 请参照登录界面的逻辑和实现 添加必要的注释即可。 


                




