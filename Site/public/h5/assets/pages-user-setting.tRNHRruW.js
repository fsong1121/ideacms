import{g as a,r as l,A as s,n as t,d as e,e as o,o as n,f as i,w as r,i as u,j as d,k as c,l as g,q as _,u as f}from"./index-ozoT3Rek.js";import{_ as m}from"./uv-loading-page.tTreRwcr.js";import{_ as p,r as h}from"./uni-app.es.Cr4agT_b.js";import{_ as w}from"./uv-icon.uym7Qx1m.js";const k=p({data:()=>({theme:"",loading:!0}),onShow(){let l=d(),s=a(),t={goods_id:0,url:s[s.length-1].route,platform:l.globalData.systemInfo.uniPlatform,model:l.globalData.systemInfo.deviceModel};this.$apis.visit.add(t),this.initData()},methods:{async initData(){let a=d(),l=this;await l.$onLaunched,l.theme=a.globalData.theme,await l.$apis.login.checkLogin(),l.loading=!1},loginOut(){this.$apis.login.loginOut().then((a=>{0==a.code?l({url:"/pages/user/login"}):s({title:a.msg,icon:"none",duration:2e3})}))},turnUrl(a="",s=1){a&&"#"!=a&&(1==s&&t({url:a}),2==s&&e({url:a}),3==s&&l({url:a}))}}},[["render",function(a,l,s,t,e,d){const p=h(o("uv-loading-page"),m),k=c,v=h(o("uv-icon"),w);return n(),i(k,{class:u(["content",e.theme])},{default:r((()=>[g(p,{loading:e.loading},null,8,["loading"]),e.loading?f("",!0):(n(),i(k,{key:0,class:"wrap"},{default:r((()=>[g(k,{class:"m_ctr"},{default:r((()=>[g(k,{class:"space_ctr"}),g(k,{class:"yuan_ctr",onClick:l[0]||(l[0]=a=>d.turnUrl("/pages/user/setInfo"))},{default:r((()=>[g(k,{class:"set_cell"},{default:r((()=>[g(k,null,{default:r((()=>[_("基本信息")])),_:1}),g(k,null,{default:r((()=>[g(v,{name:"arrow-right",color:"#999"})])),_:1})])),_:1})])),_:1}),g(k,{class:"yuan_ctr",onClick:l[1]||(l[1]=a=>d.turnUrl("/pages/user/setPassword"))},{default:r((()=>[g(k,{class:"set_cell"},{default:r((()=>[g(k,null,{default:r((()=>[_("登录密码")])),_:1}),g(k,null,{default:r((()=>[g(v,{name:"arrow-right",color:"#999"})])),_:1})])),_:1})])),_:1}),g(k,{class:"submit_btn",onClick:d.loginOut},{default:r((()=>[_("退出登录")])),_:1},8,["onClick"])])),_:1})])),_:1}))])),_:1},8,["class"])}],["__scopeId","data-v-425bd3d5"]]);export{k as default};
