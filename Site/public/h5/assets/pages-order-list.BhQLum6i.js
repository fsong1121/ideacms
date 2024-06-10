import{g as e,a as t,c as a,h as s,n as l,d as r,r as n,B as i,A as o,m as d,e as c,o as _,f as u,w as f,i as p,j as g,k as m,l as k,p as y,F as h,t as C,u as b,q as x,v,C as D,x as w}from"./index-C4nhL_Jx.js";import{_ as j}from"./uv-loading-page.PtN9WcWT.js";import{_ as U,r as L}from"./uni-app.es.pQKBppRq.js";import{_ as O}from"./uv-tabs.d1GkU_Qj.js";import{_ as T}from"./uv-empty.CTmrlToS.js";import"./uv-badge.3oA15bBI.js";import"./uv-icon.Nv3ytMSE.js";const I=U({data:()=>({theme:"",loading:!0,tabsTitle:[{name:"全部"},{name:"待付款"},{name:"待发货"},{name:"待收货"},{name:"已完成"}],currentTab:0,lineColor:"",dataList:[]}),onLoad(e){let t=this;Reflect.ownKeys(e).length>0&&e.hasOwnProperty("state")&&(t.currentTab=parseInt(e.state))},onShow(){let t=g(),a=e(),s={goods_id:0,url:a[a.length-1].route,platform:t.globalData.systemInfo.uniPlatform,model:t.globalData.systemInfo.deviceModel};this.$apis.visit.add(s),this.initData()},onPullDownRefresh(){t({title:"加载中"}),this.initData("refresh")},methods:{async initData(e=""){let t,l=g(),r=this;await r.$onLaunched,r.theme=l.globalData.theme,r.lineColor=l.globalData.themeColor,t=await r.$apis.order.getList({size:1e3,state:r.currentTab}),r.dataList=t.data,"refresh"==e&&a(),s(),r.loading=!1},tabChange(e){this.currentTab=e.index,t({title:"加载中"}),this.initData()},turnUrl(e="",t=1){e&&"#"!=e&&(1==t&&l({url:e}),2==t&&r({url:e}),3==t&&n({url:e}))},cancelOrder(e){let a=this;i({title:"提示",content:"确认要取消订单吗？",success(l){l.confirm&&(t({title:"加载中"}),a.$apis.order.cancelData({id:e}).then((e=>{s(),0==e.code?a.initData():o({title:e.msg,icon:"none"})})))}})},delOrder(e){let a=this;i({title:"提示",content:"确认要删除订单吗？",success(l){l.confirm&&(t({title:"加载中"}),a.$apis.order.deleteData({id:e}).then((e=>{s(),0==e.code?a.initData():o({title:e.msg,icon:"none"})})))}})},receiptOrder(e){let a=this;i({title:"提示",content:"确认已收到货了吗？",success(l){l.confirm&&(t({title:"加载中"}),a.$apis.order.receiptData({id:e}).then((e=>{s(),0==e.code?a.initData():o({title:e.msg,icon:"none"})})))}})},onCall(){let e=g();d({phoneNumber:e.globalData.siteInfo.phone})}}},[["render",function(e,t,a,s,l,r){const n=L(c("uv-loading-page"),j),i=L(c("uv-tabs"),O),o=m,d=w,g=L(c("uv-empty"),T);return _(),u(o,{class:p(["content",l.theme])},{default:f((()=>[k(n,{loading:l.loading},null,8,["loading"]),l.loading?b("",!0):(_(),y(h,{key:0},[k(o,{class:"header"},{default:f((()=>[k(o,{class:"header_ctt"},{default:f((()=>[k(i,{list:l.tabsTitle,current:l.currentTab,inactiveStyle:{color:"#999",transform:"scale(1)"},activeStyle:{color:"#333",transform:"scale(1.05)",fontWeight:"bold"},lineColor:l.lineColor,scrollable:!1,onChange:r.tabChange},null,8,["list","current","inactiveStyle","activeStyle","lineColor","onChange"])])),_:1})])),_:1}),k(o,{class:"wrap"},{default:f((()=>[k(o,{class:"space_header"}),k(o,{class:"m_ctr"},{default:f((()=>[k(o,{class:"space_ctr"}),(_(!0),y(h,null,C(l.dataList,((e,t)=>(_(),u(o,{class:"yuan_ctr",key:t},{default:f((()=>[k(o,{class:"order_list_title"},{default:f((()=>[k(o,null,{default:f((()=>[x("订单号："+v(e.order_sn)+" ["+v(e.order_type_title)+"]",1)])),_:2},1024),k(o,{class:"span"},{default:f((()=>[x(v(e.order_state),1)])),_:2},1024)])),_:2},1024),(_(!0),y(h,null,C(e.goods_list,((t,a)=>(_(),u(o,{class:"order_goods_ctr",key:a,onClick:t=>r.turnUrl("/pages/order/detail?id="+e.id)},{default:f((()=>[k(o,{class:"order_goods_pic"},{default:f((()=>[k(d,{mode:"widthFix",src:t.goods_pic},null,8,["src"])])),_:2},1024),k(o,{class:"order_goods_ctt"},{default:f((()=>[k(o,{class:"order_goods_title",style:D(""==t.spec_key_name?"-webkit-line-clamp:2;":"")},{default:f((()=>[x(v(t.goods_title),1)])),_:2},1032,["style"]),""!=t.spec_key_name?(_(),u(o,{key:0,class:"order_goods_subtitle"},{default:f((()=>[x(v(t.spec_key_name),1)])),_:2},1024)):b("",!0)])),_:2},1024),k(o,{class:"order_goods_price"},{default:f((()=>[k(o,null,{default:f((()=>[x("¥"+v(t.price),1)])),_:2},1024),k(o,null,{default:f((()=>[x("×"+v(t.amount),1)])),_:2},1024),e.order_state_no>1&&e.refund_state>0?(_(),u(o,{key:0,class:"span"},{default:f((()=>[x("退款中")])),_:1})):b("",!0)])),_:2},1024)])),_:2},1032,["onClick"])))),128)),k(o,{class:"order_price"},{default:f((()=>[k(o,{class:"price"},{default:f((()=>[k(o,{class:"span"},{default:f((()=>[x("总价¥")])),_:1}),k(o,null,{default:f((()=>[x(v(e.price),1)])),_:2},1024)])),_:2},1024),"integral"!=e.order_type?(_(),u(o,{key:0,class:"pay_price"},{default:f((()=>[k(o,{class:"span"},{default:f((()=>[x("实付款¥")])),_:1}),k(o,null,{default:f((()=>[x(v(e.pay_price),1)])),_:2},1024)])),_:2},1024)):(_(),u(o,{key:1,class:"pay_price"},{default:f((()=>[k(o,{class:"span"},{default:f((()=>[x("实付积分")])),_:1}),k(o,{style:{"padding-left":"4rpx"}},{default:f((()=>[x(v(e.exchange_integral),1)])),_:2},1024)])),_:2},1024))])),_:2},1024),k(o,{class:"order_list_btn_ctr"},{default:f((()=>["已取消"==e.order_state?(_(),u(o,{key:0,class:"order_list_btn",onClick:t=>r.delOrder(e.id)},{default:f((()=>[x("删除订单")])),_:2},1032,["onClick"])):b("",!0),"待付款"==e.order_state?(_(),y(h,{key:1},[k(o,{class:"order_list_btn",onClick:t=>r.cancelOrder(e.id)},{default:f((()=>[x("取消订单")])),_:2},1032,["onClick"]),k(o,{class:"order_list_btn_on",onClick:t=>r.turnUrl("/pages/pay/index?sn="+e.order_sn)},{default:f((()=>[x("去付款")])),_:2},1032,["onClick"])],64)):b("",!0),"待发货"==e.order_state?(_(),y(h,{key:2},[k(o,{class:"order_list_btn"},{default:f((()=>[k(o,{onClick:r.onCall},{default:f((()=>[x("提醒发货")])),_:1},8,["onClick"])])),_:1}),1==e.is_refund&&0==e.refund_state?(_(),u(o,{key:0,class:"order_list_btn_on",onClick:t=>r.turnUrl("/pages/refund/index?id="+e.id)},{default:f((()=>[x("申请退款")])),_:2},1032,["onClick"])):b("",!0)],64)):b("",!0),"待收货"==e.order_state?(_(),y(h,{key:3},[1==e.is_refund&&0==e.refund_state?(_(),u(o,{key:0,class:"order_list_btn",onClick:t=>r.turnUrl("/pages/refund/index?id="+e.id)},{default:f((()=>[x("退换货")])),_:2},1032,["onClick"])):b("",!0),1==e.express_type&&""!=e.express_sn?(_(),u(o,{key:1,class:"order_list_btn",onClick:t=>r.turnUrl("/pages/order/express?id="+e.id)},{default:f((()=>[x("查看物流")])),_:2},1032,["onClick"])):b("",!0),k(o,{class:"order_list_btn_on",onClick:t=>r.receiptOrder(e.id)},{default:f((()=>[x("确认收货")])),_:2},1032,["onClick"])],64)):b("",!0),"已完成"==e.order_state?(_(),y(h,{key:4},[1==e.is_refund&&0==e.refund_state?(_(),u(o,{key:0,class:"order_list_btn",onClick:t=>r.turnUrl("/pages/refund/index?id="+e.id)},{default:f((()=>[x("退换货")])),_:2},1032,["onClick"])):b("",!0),1==e.express_type&&""!=e.express_sn?(_(),u(o,{key:1,class:"order_list_btn",onClick:t=>r.turnUrl("/pages/order/express?id="+e.id)},{default:f((()=>[x("查看物流")])),_:2},1032,["onClick"])):b("",!0)],64)):b("",!0)])),_:2},1024)])),_:2},1024)))),128)),l.dataList.length<1?(_(),u(o,{key:0,class:"none_ctr"},{default:f((()=>[k(g,{icon:"/static/images/none.png",text:"暂无订单"})])),_:1})):b("",!0)])),_:1})])),_:1})],64))])),_:1},8,["class"])}],["__scopeId","data-v-318ce18e"]]);export{I as default};
