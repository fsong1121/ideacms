import{g as a,a as t,b as e,c as l,h as s,n as i,d as o,r as n,o as r,e as d,w as c,f as u,i as f,j as h,k as m,l as _,F as g,q as p,t as b,v,p as y,u as C}from"./index-8b182b74.js";import{_ as I}from"./uv-loading-page.792746ec.js";import{_ as w,r as D}from"./uni-app.es.90f08a56.js";import{_ as T}from"./uv-tabs.cc31570f.js";import{_ as j}from"./uv-icon.70699d37.js";import{_ as k}from"./uv-empty.ae111bf2.js";import"./uv-badge.8ff43380.js";const x=w({data:()=>({theme:"",loading:!0,tabsTitle:[],currentTab:0,lineColor:"",catId:0,dataList:[]}),onLoad(a){let t=this;Reflect.ownKeys(a).length>0&&(a.hasOwnProperty("id")&&(t.catId=a.id),a.hasOwnProperty("tab")&&(t.currentTab=parseInt(a.tab)))},onShow(){let t=f(),e=a(),l={goods_id:0,url:e[e.length-1].route,platform:t.globalData.systemInfo.uniPlatform,model:t.globalData.systemInfo.deviceModel};this.$apis.visit.add(l),this.initData()},onPullDownRefresh(){t({title:"加载中"}),this.initData("refresh")},methods:{async initData(a=""){let t,i=f(),o=this,n={},r=[];if(await o.$onLaunched,o.theme=i.globalData.theme,o.lineColor=i.globalData.themeColor,0==o.tabsTitle.length){t=await o.$apis.article.getCategory();for(let a=0;a<t.data.length;a++)0==a&&0==o.catId&&(o.catId=t.data[a].id),n={},n.name=t.data[a].title,n.id=t.data[a].id,r.push(n);o.tabsTitle=r}t=await o.$apis.article.getList({cat:o.catId}),o.dataList=t.data,e({title:t.cat_title}),"refresh"==a&&l(),s(),o.loading=!1},tabChange(a){let e=this;e.currentTab=a.index,e.catId=e.tabsTitle[a.index].id,t({title:"加载中"}),e.initData()},turnUrl(a="",t=1){a&&"#"!=a&&(1==t&&i({url:a}),2==t&&o({url:a}),3==t&&n({url:a}))}}},[["render",function(a,t,e,l,s,i){const o=D(v("uv-loading-page"),I),n=D(v("uv-tabs"),T),f=h,w=D(v("uv-icon"),j),x=D(v("uv-empty"),k);return r(),d(f,{class:u(["content",s.theme])},{default:c((()=>[m(o,{loading:s.loading},null,8,["loading"]),s.loading?b("",!0):(r(),_(g,{key:0},[m(f,{class:"header"},{default:c((()=>[m(f,{class:"header_ctt"},{default:c((()=>[m(n,{list:s.tabsTitle,current:s.currentTab,inactiveStyle:{color:"#999",transform:"scale(1)"},activeStyle:{color:"#333",transform:"scale(1.05)",fontWeight:"bold"},lineColor:s.lineColor,onChange:i.tabChange},null,8,["list","current","inactiveStyle","activeStyle","lineColor","onChange"])])),_:1})])),_:1}),m(f,{class:"wrap"},{default:c((()=>[m(f,{class:"space_header"}),m(f,{class:"m_ctr"},{default:c((()=>[m(f,{class:"space_ctr"}),(r(!0),_(g,null,p(s.dataList,((a,t)=>(r(),d(f,{key:t,class:"yuan_ctr",onClick:t=>i.turnUrl("/pages/article/detail?id="+a.id)},{default:c((()=>[m(f,{class:"article_list_title"},{default:c((()=>[y(C(a.title),1)])),_:2},1024),m(f,{class:"article_list_info"},{default:c((()=>[y(C(a.survey),1)])),_:2},1024),m(f,{class:"article_list_date"},{default:c((()=>[m(f,{class:"ico"},{default:c((()=>[m(w,{name:"clock-o","custom-prefix":"custom-icon",size:"24rpx",color:"#999"})])),_:1}),m(f,null,{default:c((()=>[y(C(a.add_date),1)])),_:2},1024)])),_:2},1024)])),_:2},1032,["onClick"])))),128)),s.dataList.length<1?(r(),d(f,{key:0,class:"none_ctr"},{default:c((()=>[m(x,{icon:"/static/images/none.png",text:"暂无文章"})])),_:1})):b("",!0)])),_:1})])),_:1})],64))])),_:1},8,["class"])}],["__scopeId","data-v-cdebad02"]]);export{x as default};
