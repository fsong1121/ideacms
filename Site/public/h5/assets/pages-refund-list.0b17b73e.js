import{g as a,a as e,c as t,h as s,n as l,d as n,r as i,B as r,A as o,o as d,e as c,w as u,f as _,i as f,j as p,k as g,l as m,F as h,q as y,t as k,v as b,p as C,u as v,C as D,x as j}from"./index-8b182b74.js";import{_ as w}from"./uv-loading-page.792746ec.js";import{_ as x,r as L}from"./uni-app.es.90f08a56.js";import{_ as T}from"./uv-tabs.cc31570f.js";import{_ as S}from"./uv-empty.ae111bf2.js";import"./uv-badge.8ff43380.js";import"./uv-icon.70699d37.js";const $=x({data:()=>({theme:"",loading:!0,tabsTitle:[{name:"全部"},{name:"处理中"},{name:"已通过"},{name:"已驳回"}],currentTab:0,lineColor:"",dataList:[]}),onShow(){let e=f(),t=a(),s={goods_id:0,url:t[t.length-1].route,platform:e.globalData.systemInfo.uniPlatform,model:e.globalData.systemInfo.deviceModel};this.$apis.visit.add(s),this.initData()},onPullDownRefresh(){e({title:"加载中"}),this.initData("refresh")},methods:{async initData(a=""){let e,l=f(),n=this;await n.$onLaunched,n.theme=l.globalData.theme,n.lineColor=l.globalData.themeColor,e=await n.$apis.refund.getList({size:1e4,state:n.currentTab}),n.dataList=e.data,"refresh"==a&&t(),s(),n.loading=!1},tabChange(a){this.currentTab=a.index,e({title:"加载中"}),this.initData()},turnUrl(a="",e=1){a&&"#"!=a&&(1==e&&l({url:a}),2==e&&n({url:a}),3==e&&i({url:a}))},cancelOrder(a){let t=this;r({title:"提示",content:"确认要取消退款吗？",success(l){l.confirm&&(e({title:"加载中"}),t.$apis.refund.cancelData({id:a}).then((a=>{s(),0==a.code?t.initData():o({title:a.msg,icon:"none"})})))}})},delOrder(a){let t=this;r({title:"提示",content:"确认要删除退款吗？",success(n){n.confirm&&(e({title:"加载中"}),t.$apis.refund.deleteData({id:a}).then((a=>{s(),0==a.code?l({url:"/pages/refund/list"}):o({title:a.msg,icon:"none"})})))}})}}},[["render",function(a,e,t,s,l,n){const i=L(b("uv-loading-page"),w),r=L(b("uv-tabs"),T),o=p,f=j,x=L(b("uv-empty"),S);return d(),c(o,{class:_(["content",l.theme])},{default:u((()=>[g(i,{loading:l.loading},null,8,["loading"]),l.loading?k("",!0):(d(),m(h,{key:0},[g(o,{class:"header"},{default:u((()=>[g(o,{class:"header_ctt"},{default:u((()=>[g(r,{list:l.tabsTitle,current:l.currentTab,inactiveStyle:{color:"#999",transform:"scale(1)"},activeStyle:{color:"#333",transform:"scale(1.05)",fontWeight:"bold"},lineColor:l.lineColor,scrollable:!1,onChange:n.tabChange},null,8,["list","current","inactiveStyle","activeStyle","lineColor","onChange"])])),_:1})])),_:1}),g(o,{class:"wrap"},{default:u((()=>[g(o,{class:"space_header"}),g(o,{class:"m_ctr"},{default:u((()=>[g(o,{class:"space_ctr"}),(d(!0),m(h,null,y(l.dataList,((a,e)=>(d(),c(o,{class:"yuan_ctr",key:e},{default:u((()=>[g(o,{class:"order_list_title"},{default:u((()=>[g(o,null,{default:u((()=>[C("单号："+v(a.sn),1)])),_:2},1024),-2==a.state?(d(),c(o,{key:0,class:"span"},{default:u((()=>[C("已取消")])),_:1})):k("",!0),-1==a.state?(d(),c(o,{key:1,class:"span"},{default:u((()=>[C("已驳回")])),_:1})):k("",!0),0==a.state?(d(),c(o,{key:2,class:"span"},{default:u((()=>[C("退款中")])),_:1})):k("",!0),1==a.state?(d(),c(o,{key:3,class:"span"},{default:u((()=>[C("已成功")])),_:1})):k("",!0)])),_:2},1024),(d(!0),m(h,null,y(a.goods_list,((e,t)=>(d(),c(o,{class:"order_goods_ctr",key:t,onClick:e=>n.turnUrl("/pages/refund/detail?id="+a.id)},{default:u((()=>[g(o,{class:"order_goods_pic"},{default:u((()=>[g(f,{mode:"widthFix",src:e.goods_pic},null,8,["src"])])),_:2},1024),g(o,{class:"order_goods_ctt"},{default:u((()=>[g(o,{class:"order_goods_title",style:D(""==e.spec_key_name?"-webkit-line-clamp:2;":"")},{default:u((()=>[C(v(e.goods_title),1)])),_:2},1032,["style"]),""!=e.spec_key_name?(d(),c(o,{key:0,class:"order_goods_subtitle"},{default:u((()=>[C(v(e.spec_key_name),1)])),_:2},1024)):k("",!0)])),_:2},1024),g(o,{class:"order_goods_price"},{default:u((()=>[g(o,null,{default:u((()=>[C("¥"+v(e.price),1)])),_:2},1024),g(o,null,{default:u((()=>[C("×"+v(e.amount),1)])),_:2},1024)])),_:2},1024)])),_:2},1032,["onClick"])))),128)),g(o,{class:"order_price"},{default:u((()=>[a.price>0?(d(),m(h,{key:0},[g(o,{class:"price"},{default:u((()=>[g(o,{class:"span"},{default:u((()=>[C("实付款¥")])),_:1}),g(o,null,{default:u((()=>[C(v(a.pay_price),1)])),_:2},1024)])),_:2},1024),g(o,{class:"pay_price"},{default:u((()=>[g(o,{class:"span"},{default:u((()=>[C("退款¥")])),_:1}),g(o,null,{default:u((()=>[C(v(a.price),1)])),_:2},1024)])),_:2},1024)],64)):k("",!0),a.integral>0?(d(),m(h,{key:1},[g(o,{class:"price"},{default:u((()=>[g(o,{class:"span"},{default:u((()=>[C("实付积分")])),_:1}),g(o,null,{default:u((()=>[C(v(a.integral),1)])),_:2},1024)])),_:2},1024),g(o,{class:"pay_price"},{default:u((()=>[g(o,{class:"span"},{default:u((()=>[C("退积分")])),_:1}),g(o,null,{default:u((()=>[C(v(a.integral),1)])),_:2},1024)])),_:2},1024)],64)):k("",!0)])),_:2},1024),g(o,{class:"order_list_btn_ctr"},{default:u((()=>[0==a.state?(d(),c(o,{key:0,class:"order_list_btn",onClick:e=>n.cancelOrder(a.id)},{default:u((()=>[C("取消退款")])),_:2},1032,["onClick"])):k("",!0),-2==a.state?(d(),c(o,{key:1,class:"order_list_btn",onClick:e=>n.delOrder(a.id)},{default:u((()=>[C("删除退款")])),_:2},1032,["onClick"])):k("",!0)])),_:2},1024)])),_:2},1024)))),128)),l.dataList.length<1?(d(),c(o,{key:0,class:"none_ctr"},{default:u((()=>[g(x,{icon:"/static/images/none.png",text:"暂无退换货商品"})])),_:1})):k("",!0)])),_:1})])),_:1})],64))])),_:1},8,["class"])}],["__scopeId","data-v-cfa2b509"]]);export{$ as default};