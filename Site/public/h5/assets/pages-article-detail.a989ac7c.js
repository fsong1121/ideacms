import{g as a,b as t,o as e,e as s,w as o,f as i,i as n,j as l,k as d,t as r,v as c,z as f}from"./index-8b182b74.js";import{_ as g}from"./uv-loading-page.792746ec.js";import{_ as m,r as p}from"./uni-app.es.90f08a56.js";import{r as u}from"./replaceImg.54141da6.js";const h=m({data:()=>({theme:"",loading:!0,currentID:0,data:{}}),onLoad(a){let t=this;Reflect.ownKeys(a).length>0&&a.hasOwnProperty("id")&&(t.currentID=a.id),t.initData()},onShow(){let t=n(),e=a(),s={goods_id:0,url:e[e.length-1].route,platform:t.globalData.systemInfo.uniPlatform,model:t.globalData.systemInfo.deviceModel};this.$apis.visit.add(s)},methods:{async initData(){let a,e=n(),s=this;await s.$onLaunched,s.theme=e.globalData.theme,a=await s.$apis.article.getData({id:s.currentID}),a.data.info=u.formatRichText(a.data.info),a.data.info=a.data.info.replace(/src=\"/g,'src="'+e.globalData.siteInfo.url),s.data=a.data,t({title:a.data.title}),s.loading=!1}}},[["render",function(a,t,n,m,u,h){const D=p(c("uv-loading-page"),g),_=f,I=l;return e(),s(I,{class:i(["content",u.theme])},{default:o((()=>[d(D,{loading:u.loading},null,8,["loading"]),u.loading?r("",!0):(e(),s(I,{key:0,class:"wrap"},{default:o((()=>[d(I,{class:"article_info"},{default:o((()=>[d(_,{space:"nbsp",nodes:u.data.info},null,8,["nodes"])])),_:1})])),_:1}))])),_:1},8,["class"])}],["__scopeId","data-v-27cb555a"]]);export{h as default};