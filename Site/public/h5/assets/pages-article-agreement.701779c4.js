import{g as a,b as e,o as t,e as s,w as o,f as n,i as l,j as i,k as r,t as d,v as c,z as p}from"./index-61bca09a.js";import{_ as f}from"./uv-loading-page.a3cea9e4.js";import{_ as g,r as u}from"./uni-app.es.c541313a.js";import{r as m}from"./replaceImg.54141da6.js";const h=g({data:()=>({theme:"",loading:!0,currentType:"user",info:""}),onLoad(a){let e=this;Reflect.ownKeys(a).length>0&&a.hasOwnProperty("type")&&(e.currentType=a.type),e.initData()},onShow(){let e=l(),t=a(),s={goods_id:0,url:t[t.length-1].route,platform:e.globalData.systemInfo.uniPlatform,model:e.globalData.systemInfo.deviceModel};this.$apis.visit.add(s)},methods:{async initData(){let a,t,s,o=l(),n=this;await n.$onLaunched,n.theme=o.globalData.theme,a=await n.$apis.config.getAgreement(),"user"==n.currentType?(s="用户协议",t=a.data.user):(s="隐私协议",t=a.data.privacy),t=m.formatRichText(t),t=t.replace(/src=\"/g,'src="'+o.globalData.siteInfo.url),n.info=t,e({title:s}),n.loading=!1}}},[["render",function(a,e,l,g,m,h){const y=u(c("uv-loading-page"),f),_=p,v=i;return t(),s(v,{class:n(["content",m.theme])},{default:o((()=>[r(y,{loading:m.loading},null,8,["loading"]),m.loading?d("",!0):(t(),s(v,{key:0,class:"wrap"},{default:o((()=>[r(v,{class:"article_info"},{default:o((()=>[r(_,{space:"nbsp",nodes:m.info},null,8,["nodes"])])),_:1})])),_:1}))])),_:1},8,["class"])}],["__scopeId","data-v-f6082412"]]);export{h as default};