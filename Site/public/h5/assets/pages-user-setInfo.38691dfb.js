import{g as a,T as e,H as t,A as s,a as l,h as o,U as i,K as n,o as r,e as u,w as d,f as c,i as m,j as f,k as p,p as v,t as h,v as _,x as g,M as b,N as I,O as w}from"./index-8b182b74.js";import{_ as y}from"./uv-loading-page.792746ec.js";import{_ as $,r as k}from"./uni-app.es.90f08a56.js";const A=$({data:()=>({theme:"",loading:!0,userInfo:{},newAvatar:""}),onLoad(){this.initData()},onShow(){let e=m(),t=a(),s={goods_id:0,url:t[t.length-1].route,platform:e.globalData.systemInfo.uniPlatform,model:e.globalData.systemInfo.deviceModel};this.$apis.visit.add(s)},methods:{async initData(){let a,e=m(),t=this;await t.$onLaunched,t.theme=e.globalData.theme,await t.$apis.login.checkLogin(),a=await t.$apis.user.getData(),t.userInfo=a.data,t.loading=!1},onChooseAvatar(a){let e=this,t=a.detail.avatarUrl;e.$apis.upload.save({file:t,dir:"avatar"}).then((a=>{0==a.code&&(e.userInfo.avatar=a.data.src,e.newAvatar=a.data.file)}))},chooseImage(){let a=this;e({count:1,sizeType:["compressed"],success:function(e){let t=e.tempFilePaths[0];a.$apis.upload.save({file:t,dir:"avatar"}).then((e=>{0==e.code&&(a.userInfo.avatar=e.data.src,a.newAvatar=e.data.file)}))}})},formSubmit(a){let e=this,r=a.detail.value;r.avatar=e.newAvatar,r.form_token=t("formToken")||"",uni.$uv.throttle((function(){let a=r.name.replace(/\s/g,""),t=r.tel.replace(/\s/g,"");""!=a?uni.$uv.test.mobile(t)?(l({title:"提交中"}),e.$apis.user.saveInfo(r).then((a=>{o(),0==a.code?(s({title:"修改成功"}),setTimeout((()=>{i(),n({delta:1})}),1500)):s({title:a.msg,icon:"none",duration:2e3})}))):s({title:"手机号错误",icon:"error"}):s({title:"会员昵称为空",icon:"error"})}))}}},[["render",function(a,e,t,s,l,o){const i=k(_("uv-loading-page"),y),n=f,m=g,$=b,A=I,D=w;return r(),u(n,{class:c(["content",l.theme])},{default:d((()=>[p(i,{loading:l.loading},null,8,["loading"]),l.loading?h("",!0):(r(),u(D,{key:0,onSubmit:o.formSubmit},{default:d((()=>[p(n,{class:"wrap"},{default:d((()=>[p(n,{class:"m_ctr"},{default:d((()=>[p(n,{class:"space_ctr"}),p(n,{class:"yuan_ctr"},{default:d((()=>[p(n,{class:"avatar_ctr"},{default:d((()=>[p(n,{class:"avatar_btn",onClick:e[0]||(e[0]=a=>o.chooseImage())}),p(m,{mode:"widthFix",src:l.userInfo.avatar},null,8,["src"])])),_:1}),p(n,{class:"input_ctr"},{default:d((()=>[p(n,{class:"input_title"},{default:d((()=>[v("会员昵称")])),_:1}),p($,{name:"name",value:l.userInfo.nickname,placeholder:"会员昵称"},null,8,["value"])])),_:1}),p(n,{class:"input_ctr",style:{border:"0",padding:"0",margin:"0"}},{default:d((()=>[p(n,{class:"input_title"},{default:d((()=>[v("手机号码")])),_:1}),p($,{name:"tel",value:l.userInfo.mobile,disabled:!0,type:"number",placeholder:"11位手机号码"},null,8,["value"])])),_:1})])),_:1}),p(A,{class:"submit_btn","form-type":"submit"},{default:d((()=>[v("确认提交")])),_:1})])),_:1})])),_:1})])),_:1},8,["onSubmit"]))])),_:1},8,["class"])}],["__scopeId","data-v-0ac3f8a3"]]);export{A as default};
