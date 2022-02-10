# 2018.03.26
1. 增加菜单权限的基础中间件，验证非开发模式不允许进行菜单的任何操作。
2. 增加【基础模块】的名称配置，为空则不显示。


# 数据库
1. users 用户账号表
2. menus  菜单存储表

# 数据库迁移
已经增加数据库迁移文件，数据库表前缀为 mbuser_

# 数据填充
执行  php artisan mbcore:baseuser 命令，可以填充初始账号数据，和一个测试菜单按钮。

# 发布包中资源 
php artisan vendor:publish --tag=public --force 

# 已完成功能
1. 菜单管理，增加、编辑功能
2. 后台账号管理，增加、编辑功能
3. login页面，可以配置背景和标题
4. 增加主页view可以进行配置
5. 增加主页路由的设置，并且为最高级别
6. 父级菜单选择逻辑及验证前端显示逻辑还需要进一步提升(父级菜单不能是自己的父级)
7. 菜单分组功能
8. 菜单排序字段的使用 
9. 菜单列表页面，展示逻辑修改
10. 后台用户权限升级，并制作演示外部验证权限方法

# bug
1. 修正config发布后识别引起的问题，cofig的前缀为：mbcore_baseuser
2. 应用安装二级目录时，样式丢失，对config的baseuser_assets_path增加依赖包core的配置判断
3. 修正config部分验证mbcore_mcore.app_install_way 条件操作。

# 登录地址
/login/login

# 相关配置说明
1. baseuser_development 非开发模式请设置为false
2. baseuser_assets_path 应用样式路径，如不使用默认资源，修改此配置。
3. baseuser_name 登录页面显示名字
4. baseuser_background_image  后台背景图片
5. baseuser_homeView  首页模板名称
6. baseuser_homeRoute  首页路由名称
7. baseuser_menuGroup  菜单分组
8. baseuser_roles_home_subroles  主页显示等级和标识名称
