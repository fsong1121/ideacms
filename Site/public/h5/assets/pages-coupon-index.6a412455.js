import{g as a,h as t,a as e,n as s,d as l,r as n,A as o,o as i,e as c,w as r,f as u,i as d,j as _,k as p,l as f,F as m,q as g,t as h,v as b,p as y,u as v}from"./index-8b182b74.js";import{_ as C}from"./uv-loading-page.792746ec.js";import{_ as T,r as k}from"./uni-app.es.90f08a56.js";import{_ as D}from"./uv-tabs.cc31570f.js";import{_ as j}from"./uv-empty.ae111bf2.js";import"./uv-badge.8ff43380.js";import"./uv-icon.70699d37.js";const w=T({data:()=>({theme:"",loading:!0,tabsTitle:[{name:"可领取"},{name:"可使用"},{name:"已使用"},{name:"已失效"}],currentTab:0,lineColor:"",dataList:[]}),onLoad(a){let t=this;Reflect.ownKeys(a).length>0&&a.hasOwnProperty("state")&&(t.currentTab=parseInt(a.state))},onShow(){let t=d(),e=a(),s={goods_id:0,url:e[e.length-1].route,platform:t.globalData.systemInfo.uniPlatform,model:t.globalData.systemInfo.deviceModel};this.$apis.visit.add(s),this.initData()},methods:{async initData(){let a,e=d(),s=this;await s.$onLaunched,s.theme=e.globalData.theme,s.lineColor=e.globalData.themeColor,a=await s.$apis.coupon.getList({state:s.currentTab}),s.dataList=a.data,t(),s.loading=!1},tabChange(a){this.currentTab=a.index,e({title:"加载中"}),this.initData()},turnUrl(a="",t=1){a&&"#"!=a&&(1==t&&s({url:a}),2==t&&l({url:a}),3==t&&n({url:a}))},getCoupon(a){let s=this;e({title:"加载中"}),s.$apis.coupon.saveData({uuid:a}).then((a=>{t(),0==a.code?o({title:"领取成功",icon:"success",duration:1500,success:()=>{setTimeout((()=>{s.initData()}),1500)}}):o({title:a.msg,icon:"none",duration:2e3})}))}}},[["render",function(a,t,e,s,l,n){const o=k(b("uv-loading-page"),C),d=k(b("uv-tabs"),D),T=_,w=k(b("uv-empty"),j);return i(),c(T,{class:u(["content",l.theme])},{default:r((()=>[p(o,{loading:l.loading},null,8,["loading"]),l.loading?h("",!0):(i(),f(m,{key:0},[p(T,{class:"header"},{default:r((()=>[p(T,{class:"header_ctt"},{default:r((()=>[p(d,{list:l.tabsTitle,current:l.currentTab,inactiveStyle:{color:"#999",transform:"scale(1)"},activeStyle:{color:"#333",transform:"scale(1.05)",fontWeight:"bold"},lineColor:l.lineColor,scrollable:!1,onChange:n.tabChange},null,8,["list","current","inactiveStyle","activeStyle","lineColor","onChange"])])),_:1})])),_:1}),p(T,{class:"wrap"},{default:r((()=>[p(T,{class:"space_header"}),p(T,{class:"m_ctr"},{default:r((()=>[p(T,{class:"space_ctr"}),(i(!0),f(m,null,g(l.dataList,((a,e)=>(i(),c(T,{class:"coupon_ctr",key:e},{default:r((()=>[p(T,{class:"coupon_left"},{default:r((()=>[p(T,{class:"coupon_title"},{default:r((()=>[y(v(a.title)+"("+v(a.use_type_title)+")",1)])),_:2},1024),p(T,{class:"coupon_price"},{default:r((()=>[p(T,null,{default:r((()=>[y("¥"+v(a.cut_price),1)])),_:2},1024),p(T,{class:"span"},{default:r((()=>[y("满"+v(a.min_price)+"元可用",1)])),_:2},1024)])),_:2},1024),p(T,{class:"coupon_date"},{default:r((()=>[y(v(a.b_date)+"-"+v(a.e_date),1)])),_:2},1024)])),_:2},1024),p(T,{class:"coupon_fg"}),p(T,{class:"coupon_right"},{default:r((()=>[0==l.currentTab?(i(),c(T,{key:0,class:"coupon_btn",onClick:t=>n.getCoupon(a.uuid)},{default:r((()=>[y("立即领取")])),_:2},1032,["onClick"])):h("",!0),1==l.currentTab?(i(),c(T,{key:1,class:"coupon_btn",onClick:t[0]||(t[0]=a=>n.turnUrl("/pages/index/index",2))},{default:r((()=>[y("去使用")])),_:1})):h("",!0),2==l.currentTab?(i(),c(T,{key:2,class:"coupon_btn_none"},{default:r((()=>[y("已使用")])),_:1})):h("",!0),3==l.currentTab?(i(),c(T,{key:3,class:"coupon_btn_none"},{default:r((()=>[y("已失效")])),_:1})):h("",!0)])),_:2},1024)])),_:2},1024)))),128)),l.dataList.length<1?(i(),c(T,{key:0,class:"none_ctr"},{default:r((()=>[p(w,{icon:"/static/images/none.png",text:"暂无相关优惠券"})])),_:1})):h("",!0)])),_:1})])),_:1})],64))])),_:1},8,["class"])}],["__scopeId","data-v-463c178e"]]);export{w as default};