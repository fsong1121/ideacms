import{g as a,n as s,d as t,r as e,A as l,e as o,o as n,f as i,w as c,i as r,j as d,k as u,l as h,p as _,q as f,F as p,t as g,u as m,M as y,v as k}from"./index-C4nhL_Jx.js";import{_ as v}from"./uv-loading-page.PtN9WcWT.js";import{_ as b,r as C}from"./uni-app.es.pQKBppRq.js";import{_ as D}from"./uv-icon.Nv3ytMSE.js";const I=b({data:()=>({theme:"",loading:!1,keys:"",dataList:[]}),onLoad(){this.initData()},onShow(){let s=d(),t=a(),e={goods_id:0,url:t[t.length-1].route,platform:s.globalData.systemInfo.uniPlatform,model:s.globalData.systemInfo.deviceModel};this.$apis.visit.add(e)},methods:{async initData(){let a=d(),s=this;await s.$onLaunched,s.theme=a.globalData.theme,s.dataList=a.globalData.siteInfo.hot_key},turnUrl(a="",l=1){a&&"#"!=a&&(1==l&&s({url:a}),2==l&&t({url:a}),3==l&&e({url:a}))},inputTyping(a){this.keys=a.detail.value},goSearch(){var a=this.keys.replace(/\s+/g,"");""==a?l({title:"关键词为空",icon:"error"}):s({url:"/pages/search/list?key="+a})}}},[["render",function(a,s,t,e,l,d){const b=C(o("uv-loading-page"),v),I=C(o("uv-icon"),D),j=u,L=y;return n(),i(j,{class:r(["content",l.theme])},{default:c((()=>[h(b,{loading:l.loading},null,8,["loading"]),l.loading?m("",!0):(n(),_(p,{key:0},[h(j,{class:"header"},{default:c((()=>[h(j,{class:"header_ctt"},{default:c((()=>[h(j,{class:"search_ctr"},{default:c((()=>[h(j,{class:"search_ctt"},{default:c((()=>[h(j,{class:"search_ico"},{default:c((()=>[h(I,{name:"search","custom-prefix":"custom-icon"})])),_:1}),h(L,{type:"text",class:"search_input",placeholder:"输入您喜爱的商品搜一下",onInput:d.inputTyping,onConfirm:d.goSearch},null,8,["onInput","onConfirm"])])),_:1}),h(j,{class:"search_btn",onClick:d.goSearch},{default:c((()=>[f("搜索")])),_:1},8,["onClick"])])),_:1})])),_:1})])),_:1}),h(j,{class:"wrap"},{default:c((()=>[h(j,{class:"space_header"}),h(j,{class:"hot_search"},{default:c((()=>[h(j,{class:"hot_search_title"},{default:c((()=>[f("热门搜索")])),_:1}),(n(!0),_(p,null,g(l.dataList,((a,s)=>(n(),i(j,{key:s},{default:c((()=>[h(j,{class:"hot_search_cell",onClick:s=>d.turnUrl("/pages/search/list?key="+a)},{default:c((()=>[f(k(a),1)])),_:2},1032,["onClick"])])),_:2},1024)))),128))])),_:1})])),_:1})],64))])),_:1},8,["class"])}],["__scopeId","data-v-b777c79f"]]);export{I as default};