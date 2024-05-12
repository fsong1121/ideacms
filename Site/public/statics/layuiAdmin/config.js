/**
 * setter
 */

// 初始化配置
layui.define(['all'], function(exports){
  exports('setter', {
    paths: { // v1.9.0 及以上版本的写法
      core: layui.cache.base + 'adminui/dist/', // 核心库所在目录
      views: layui.cache.base + 'views/', // 动态模板所在目录
      modules: layui.cache.base + 'modules/', // 业务模块所在目录
      base: layui.cache.base // 记录静态资源所在基础目录
    },
    /* v1.9.0 之前的写法
    // views: layui.cache.base + 'views/', // 业务视图所在目录
    // base: layui.cache.base, // 记录静态资源所在基础目录
    */
    
    container: 'LAY_app', // 容器 ID
    entry: 'index', // 默认视图文件名
    engine: '.html', // 视图文件后缀名
    pageTabs: true, // 是否开启页面选项卡功能。 iframe 版推荐开启
    refreshCurrPage: true, // 当跳转页面 url 与当前页 url 相同时，是否自动执行刷新 --- 2.0+
    
    name: 'layuiAdmin', // 系统名称
    tableName: 'layuiAdmin', // 本地存储表名
    MOD_NAME: 'admin', // 模块事件名

    // 记录
    record: {
      url: false // 是否开启 iframe 跳转时 url 记忆功能
    },
    
    debug: true, // 是否开启调试模式。如开启，接口异常时会抛出异常 URL 等信息

    // 自定义请求字段
    request: {
      tokenName: false // 自动携带 token 的字段名（如：access_token）。可设置 false 不携带。
    },
    
    // 自定义响应字段
    response: {
      statusName: 'code', // 数据状态的字段名称
      statusCode: {
        ok: 0, // 数据状态一切正常的状态码
        logout: 1001 // 登录状态失效的状态码
      },
      msgName: 'msg', // 状态信息的字段名称
      dataName: 'data' // 数据详情的字段名称
    },

    // 配置业务模块目录中的特殊模块
    extend: {
      layim: 'layim/layim' // layim
    },
    
    // 主题配置
    theme: {
      // 内置主题配色方案
      color: [{
        main: '#20222A' //主题色
        ,selected: '#1890ff' //选中色
        ,alias: 'default' //默认别名
      },{
        main: '#344058'
        ,selected: '#1890ff'
        ,alias: 'ocean' //海洋
      },{
        main: '#24262F'
        ,logo: '#3A3D49'
        ,selected: '#1890ff'
        ,alias: 'classic-black' //经典黑
      }],
      
      // 初始的颜色索引，对应上面的配色方案数组索引
      // 如果本地已经有主题色记录，则以本地记录为优先，除非请求本地数据（localStorage）
      initColorIndex: 0
    }
  });
});