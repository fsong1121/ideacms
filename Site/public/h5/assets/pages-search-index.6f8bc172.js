import{g as a,n as s,d as t,r as e,A as l,o,e as n,w as i,f as c,i as r,j as d,k as u,l as h,p as _,F as f,q as p,t as g,v as m,M as y,u as k}from"./index-8b182b74.js";import{_ as v}from"./uv-loading-page.792746ec.js";import{_ as b,r as C}from"./uni-app.es.90f08a56.js";import{_ as D}from"./uv-icon.70699d37.js";const I=b({data:()=>({theme:"",loading:!1,keys:"",dataList:[]}),onLoad(){this.initData()},onShow(){let s=r(),t=a(),e={goods_id:0,url:t[t.length-1].route,platform:s.globalData.systemInfo.uniPlatform,model:s.globalData.systemInfo.deviceModel};this.$apis.visit.add(e)},methods:{async initData(){let a=r(),s=this;await s.$onLaunched,s.theme=a.globalData.theme,s.dataList=a.globalData.siteInfo.hot_key},turnUrl(a="",l=1){a&&"#"!=a&&(1==l&&s({url:a}),2==l&&t({url:a}),3==l&&e({url:a}))},inputTyping(a){this.keys=a.detail.value},goSearch(){var a=this.keys.replace(/\s+/g,"");""==a?l({title:"关键词为空",icon:"error"}):s({url:"/pages/search/list?key="+a})}}},[["render",function(a,s,t,e,l,r){const b=C(m("uv-loading-page"),v),I=C(m("uv-icon"),D),j=d,L=y;return o(),n(j,{class:c(["content",l.theme])},{default:i((()=>[u(b,{loading:l.loading},null,8,["loading"]),l.loading?g("",!0):(o(),h(f,{key:0},[u(j,{class:"header"},{default:i((()=>[u(j,{class:"header_ctt"},{default:i((()=>[u(j,{class:"search_ctr"},{default:i((()=>[u(j,{class:"search_ctt"},{default:i((()=>[u(j,{class:"search_ico"},{default:i((()=>[u(I,{name:"search","custom-prefix":"custom-icon"})])),_:1}),u(L,{type:"text",class:"search_input",placeholder:"输入您喜爱的商品搜一下",onInput:r.inputTyping,onConfirm:r.goSearch},null,8,["onInput","onConfirm"])])),_:1}),u(j,{class:"search_btn",onClick:r.goSearch},{default:i((()=>[_("搜索")])),_:1},8,["onClick"])])),_:1})])),_:1})])),_:1}),u(j,{class:"wrap"},{default:i((()=>[u(j,{class:"space_header"}),u(j,{class:"hot_search"},{default:i((()=>[u(j,{class:"hot_search_title"},{default:i((()=>[_("热门搜索")])),_:1}),(o(!0),h(f,null,p(l.dataList,((a,s)=>(o(),n(j,{key:s},{default:i((()=>[u(j,{class:"hot_search_cell",onClick:s=>r.turnUrl("/pages/search/list?key="+a)},{default:i((()=>[_(k(a),1)])),_:2},1032,["onClick"])])),_:2},1024)))),128))])),_:1})])),_:1})],64))])),_:1},8,["class"])}],["__scopeId","data-v-b777c79f"]]);export{I as default};
