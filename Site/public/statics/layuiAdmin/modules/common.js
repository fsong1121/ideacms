/**
 * common demo
 */
 
layui.define(function(exports){
  var $ = layui.$
  ,layer = layui.layer
  ,laytpl = layui.laytpl
  ,setter = layui.setter
  ,view = layui.view
  ,admin = layui.admin
  
  //公共业务的逻辑处理可以写在此处，切换任何页面都会执行
  var siteUrl = layui.setter.paths.base.replace('/statics/layuiAdmin/','');

  //退出
  admin.events.logout = function(){
    //执行退出接口
    $.ajax({
      type:"post",
      url:siteUrl + "/admin/login/loginOut.html",
      data: {},
      dataType:"json",
      success:function(res){
        parent.location.href = siteUrl + "/admin/login/index.html";
      }
    });
  };

  //清除缓存
  admin.events.clearCache = function(){
    layer.confirm('确定要清除缓存吗?', function(){
      $.ajax({
        type:"post",
        url:siteUrl + "/admin/index/clearCache.html",
        data: {},
        dataType:"json",
        success:function(res){
          parent.location.href = siteUrl + "/admin/index.html";
        }
      });
    });
  };

  
  //对外暴露的接口
  exports('common', {});
});