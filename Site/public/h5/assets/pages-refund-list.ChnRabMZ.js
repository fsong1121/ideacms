import{g as a,a as e,c as t,h as s,n as l,d as n,r as i,B as r,A as o,e as d,o as c,f as u,w as _,i as f,j as p,k as g,l as m,p as h,F as y,t as k,u as b,q as C,v,C as D,x as j}from"./index-C4nhL_Jx.js";import{_ as w}from"./uv-loading-page.PtN9WcWT.js";import{_ as x,r as L}from"./uni-app.es.pQKBppRq.js";import{_ as T}from"./uv-tabs.d1GkU_Qj.js";import{_ as S}from"./uv-empty.CTmrlToS.js";import"./uv-badge.3oA15bBI.js";import"./uv-icon.Nv3ytMSE.js";const $=x({data:()=>({theme:"",loading:!0,tabsTitle:[{name:"全部"},{name:"处理中"},{name:"已通过"},{name:"已驳回"}],currentTab:0,lineColor:"",dataList:[]}),onShow(){let e=p(),t=a(),s={goods_id:0,url:t[t.length-1].route,platform:e.globalData.systemInfo.uniPlatform,model:e.globalData.systemInfo.deviceModel};this.$apis.visit.add(s),this.initData()},onPullDownRefresh(){e({title:"加载中"}),this.initData("refresh")},methods:{async initData(a=""){let e,l=p(),n=this;await n.$onLaunched,n.theme=l.globalData.theme,n.lineColor=l.globalData.themeColor,e=await n.$apis.refund.getList({size:1e4,state:n.currentTab}),n.dataList=e.data,"refresh"==a&&t(),s(),n.loading=!1},tabChange(a){this.currentTab=a.index,e({title:"加载中"}),this.initData()},turnUrl(a="",e=1){a&&"#"!=a&&(1==e&&l({url:a}),2==e&&n({url:a}),3==e&&i({url:a}))},cancelOrder(a){let t=this;r({title:"提示",content:"确认要取消退款吗？",success(l){l.confirm&&(e({title:"加载中"}),t.$apis.refund.cancelData({id:a}).then((a=>{s(),0==a.code?t.initData():o({title:a.msg,icon:"none"})})))}})},delOrder(a){let t=this;r({title:"提示",content:"确认要删除退款吗？",success(n){n.confirm&&(e({title:"加载中"}),t.$apis.refund.deleteData({id:a}).then((a=>{s(),0==a.code?l({url:"/pages/refund/list"}):o({title:a.msg,icon:"none"})})))}})}}},[["render",function(a,e,t,s,l,n){const i=L(d("uv-loading-page"),w),r=L(d("uv-tabs"),T),o=g,p=j,x=L(d("uv-empty"),S);return c(),u(o,{class:f(["content",l.theme])},{default:_((()=>[m(i,{loading:l.loading},null,8,["loading"]),l.loading?b("",!0):(c(),h(y,{key:0},[m(o,{class:"header"},{default:_((()=>[m(o,{class:"header_ctt"},{default:_((()=>[m(r,{list:l.tabsTitle,current:l.currentTab,inactiveStyle:{color:"#999",transform:"scale(1)"},activeStyle:{color:"#333",transform:"scale(1.05)",fontWeight:"bold"},lineColor:l.lineColor,scrollable:!1,onChange:n.tabChange},null,8,["list","current","inactiveStyle","activeStyle","lineColor","onChange"])])),_:1})])),_:1}),m(o,{class:"wrap"},{default:_((()=>[m(o,{class:"space_header"}),m(o,{class:"m_ctr"},{default:_((()=>[m(o,{class:"space_ctr"}),(c(!0),h(y,null,k(l.dataList,((a,e)=>(c(),u(o,{class:"yuan_ctr",key:e},{default:_((()=>[m(o,{class:"order_list_title"},{default:_((()=>[m(o,null,{default:_((()=>[C("单号："+v(a.sn),1)])),_:2},1024),-2==a.state?(c(),u(o,{key:0,class:"span"},{default:_((()=>[C("已取消")])),_:1})):b("",!0),-1==a.state?(c(),u(o,{key:1,class:"span"},{default:_((()=>[C("已驳回")])),_:1})):b("",!0),0==a.state?(c(),u(o,{key:2,class:"span"},{default:_((()=>[C("退款中")])),_:1})):b("",!0),1==a.state?(c(),u(o,{key:3,class:"span"},{default:_((()=>[C("已成功")])),_:1})):b("",!0)])),_:2},1024),(c(!0),h(y,null,k(a.goods_list,((e,t)=>(c(),u(o,{class:"order_goods_ctr",key:t,onClick:e=>n.turnUrl("/pages/refund/detail?id="+a.id)},{default:_((()=>[m(o,{class:"order_goods_pic"},{default:_((()=>[m(p,{mode:"widthFix",src:e.goods_pic},null,8,["src"])])),_:2},1024),m(o,{class:"order_goods_ctt"},{default:_((()=>[m(o,{class:"order_goods_title",style:D(""==e.spec_key_name?"-webkit-line-clamp:2;":"")},{default:_((()=>[C(v(e.goods_title),1)])),_:2},1032,["style"]),""!=e.spec_key_name?(c(),u(o,{key:0,class:"order_goods_subtitle"},{default:_((()=>[C(v(e.spec_key_name),1)])),_:2},1024)):b("",!0)])),_:2},1024),m(o,{class:"order_goods_price"},{default:_((()=>[m(o,null,{default:_((()=>[C("¥"+v(e.price),1)])),_:2},1024),m(o,null,{default:_((()=>[C("×"+v(e.amount),1)])),_:2},1024)])),_:2},1024)])),_:2},1032,["onClick"])))),128)),m(o,{class:"order_price"},{default:_((()=>[a.price>0?(c(),h(y,{key:0},[m(o,{class:"price"},{default:_((()=>[m(o,{class:"span"},{default:_((()=>[C("实付款¥")])),_:1}),m(o,null,{default:_((()=>[C(v(a.pay_price),1)])),_:2},1024)])),_:2},1024),m(o,{class:"pay_price"},{default:_((()=>[m(o,{class:"span"},{default:_((()=>[C("退款¥")])),_:1}),m(o,null,{default:_((()=>[C(v(a.price),1)])),_:2},1024)])),_:2},1024)],64)):b("",!0),a.integral>0?(c(),h(y,{key:1},[m(o,{class:"price"},{default:_((()=>[m(o,{class:"span"},{default:_((()=>[C("实付积分")])),_:1}),m(o,null,{default:_((()=>[C(v(a.integral),1)])),_:2},1024)])),_:2},1024),m(o,{class:"pay_price"},{default:_((()=>[m(o,{class:"span"},{default:_((()=>[C("退积分")])),_:1}),m(o,null,{default:_((()=>[C(v(a.integral),1)])),_:2},1024)])),_:2},1024)],64)):b("",!0)])),_:2},1024),m(o,{class:"order_list_btn_ctr"},{default:_((()=>[0==a.state?(c(),u(o,{key:0,class:"order_list_btn",onClick:e=>n.cancelOrder(a.id)},{default:_((()=>[C("取消退款")])),_:2},1032,["onClick"])):b("",!0),-2==a.state?(c(),u(o,{key:1,class:"order_list_btn",onClick:e=>n.delOrder(a.id)},{default:_((()=>[C("删除退款")])),_:2},1032,["onClick"])):b("",!0)])),_:2},1024)])),_:2},1024)))),128)),l.dataList.length<1?(c(),u(o,{key:0,class:"none_ctr"},{default:_((()=>[m(x,{icon:"/static/images/none.png",text:"暂无退换货商品"})])),_:1})):b("",!0)])),_:1})])),_:1})],64))])),_:1},8,["class"])}],["__scopeId","data-v-cfa2b509"]]);export{$ as default};
