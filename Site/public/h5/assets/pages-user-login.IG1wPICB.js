var e,t;import{E as n,G as i,H as s,I as a,J as o,o as l,f as u,k as r,g as c,s as d,D as h,A as g,a as m,h as p,K as f,d as _,L as v,n as b,r as y,e as C,w as k,i as x,C as T,j as I,l as L,q as N,u as S,v as D,M as w,N as $,O as E}from"./index-Bnh-aLYr.js";import{_ as H,r as A}from"./uni-app.es.CdPVnhCL.js";import{_ as K,a as G}from"./uv-checkbox-group.wc67Hfdr.js";import{_ as M}from"./uv-divider.D8ugueBw.js";import{_ as j}from"./uv-icon.DWcsmyzS.js";const R=H({name:"uv-code",mixins:[n,i,{props:{seconds:{type:[String,Number],default:60},startText:{type:String,default:"获取验证码"},changeText:{type:String,default:"X秒重新获取"},endText:{type:String,default:"重新获取"},keepRunning:{type:Boolean,default:!1},uniqueKey:{type:String,default:""},...null==(t=null==(e=uni.$uv)?void 0:e.props)?void 0:t.code}}],data(){return{secNum:this.seconds,timer:null,canGetCode:!0}},mounted(){this.checkKeepRunning()},watch:{seconds:{immediate:!0,handler(e){this.secNum=e}}},methods:{checkKeepRunning(){let e=Number(s(this.uniqueKey+"_$uCountDownTimestamp"));if(!e)return this.changeEvent(this.startText);let t=Math.floor(+new Date/1e3);this.keepRunning&&e&&e>t?(this.secNum=e-t,a(this.uniqueKey+"_$uCountDownTimestamp"),this.start()):this.changeEvent(this.startText)},start(){this.timer&&(clearInterval(this.timer),this.timer=null),this.$emit("start"),this.canGetCode=!1,this.changeEvent(this.changeText.replace(/x|X/,this.secNum)),this.timer=setInterval((()=>{--this.secNum?this.changeEvent(this.changeText.replace(/x|X/,this.secNum)):(clearInterval(this.timer),this.timer=null,this.changeEvent(this.endText),this.secNum=this.seconds,this.$emit("end"),this.canGetCode=!0)}),1e3),this.setTimeToStorage()},reset(){this.canGetCode=!0,clearInterval(this.timer),this.secNum=this.seconds,this.changeEvent(this.endText)},changeEvent(e){this.$emit("change",e)},setTimeToStorage(){if(this.keepRunning&&this.timer&&this.secNum>0&&this.secNum<=this.seconds){let e=Math.floor(+new Date/1e3);o({key:this.uniqueKey+"_$uCountDownTimestamp",data:e+Number(this.secNum)})}}},unmounted(){this.setTimeToStorage(),clearTimeout(this.timer),this.timer=null}},[["render",function(e,t,n,i,s,a){const o=r;return l(),u(o,{class:"uv-code"})}]]);const q=H({data:()=>({theme:"",themeColor:"",curLogin:0,smsLogin:0,otherLogin:0,tips:"",seconds:60,sending:0,tel:"",code:"",isAgree:0,backgroundStyle:{},navHeight:0,customNavWidth:0,customNavHeight:0,customNavTop:0}),onLoad(e){let t=c();t.length>1&&d("prevUrl",t[t.length-2].route),this.initData()},onShow(){let e=I(),t=c(),n={goods_id:0,url:t[t.length-1].route,platform:e.globalData.systemInfo.uniPlatform,model:e.globalData.systemInfo.deviceModel};this.$apis.visit.add(n)},methods:{async initData(){let e=I(),t=this;await t.$onLaunched,t.theme=e.globalData.theme,t.themeColor=e.globalData.themeColor,t.smsLogin=e.globalData.siteInfo.code_login,t.otherLogin=e.globalData.siteInfo.other_login,t.curLogin=e.globalData.siteInfo.other_login;let n=0,i=0;Reflect.ownKeys(e.globalData.systemInfo).length>0&&(n=e.globalData.systemInfo.statusBarHeight,i=e.globalData.systemInfo.navHeight,t.navHeight=i,t.customNavWidth=e.globalData.systemInfo.boundingLeft,t.customNavHeight=e.globalData.systemInfo.boundingHeight,t.customNavTop=e.globalData.systemInfo.boundingTop);let s=-260;s=parseInt(s)+Number(parseInt((i+n)/(h(100)/100))),t.backgroundStyle={background:"url("+e.globalData.siteInfo.url+"/upload/pic/public/m_bg_"+e.globalData.theme+".png) left "+s+"rpx no-repeat #f6f6f6",backgroundSize:"100% auto"}},checkAgree(e){this.isAgree=e.length},changeLogin(){this.curLogin=0==this.curLogin?1:0},formSubmit(e){let t=this,n=t.isAgree;uni.$uv.throttle((function(i=e.detail.value){let a=c();if(!n)return void g({title:"请同意用户协议",icon:"none",duration:2e3});if(""==i.m_uid||""==i.m_pwd)return void g({title:"用户名或密码为空",icon:"none",duration:2e3});let o=s("pid")||"";""==o&&(o=0),m({title:"登录中"});let l={m_uid:i.m_uid,m_pwd:i.m_pwd,pid:o};t.$apis.login.login(l).then((e=>{p(),0==e.code?a.length>1?f({delta:1}):_({url:"/pages/user/index"}):g({title:e.msg,icon:"none",duration:2e3})}))}))},inputTel(e){this.tel=e.detail.value},inputCode(e){this.code=e.detail.value},codeChange(e){this.tips=e},getCode(){let e=this;uni.$uv.throttle((function(){let t=e.tel;if(e.$refs.code.canGetCode){if(!uni.$uv.test.mobile(t))return void g({title:"手机号错误",icon:"error"});m({title:"正在获取验证码"});let n={m_tel:t};e.$apis.login.sendSmsCode(n).then((t=>{0==t.code?(p(),g({title:"验证码已发送",icon:"none"}),e.$refs.code.start()):g({title:t.message,icon:"none"})}))}}))},end(){this.sending=0},start(){this.sending=1},smsSubmit(e){let t=this,n=t.isAgree;uni.$uv.throttle((function(i=e.detail.value){let a=c();if(!n)return void g({title:"请同意用户协议",icon:"none",duration:2e3});if(""==i.m_tel||""==i.m_tel)return void g({title:"手机号或验证码为空！",icon:"none",duration:2e3});let o=s("pid")||"";""==o&&(o=0),m({title:"登录中"});let l={m_tel:i.m_tel,m_code:i.m_code,pid:o};t.$apis.login.smsCodeLogin(l).then((e=>{p(),0==e.code?a.length>1?f({delta:1}):_({url:"/pages/user/index"}):g({title:e.msg,icon:"none",duration:2e3})}))}))},getPhoneNumber(e){let t=this,n=c();if(t.isAgree){if("getPhoneNumber:ok"==e.detail.errMsg){let i=e.detail.code,a="";v({provider:"weixin",success:function(e){"login:ok"==e.errMsg&&(a=e.code);let o=s("pid")||"";""==o&&(o=0),m({title:"登录中"});let l={loginCode:a,phoneCode:i,pid:o};t.$apis.login.miniappLogin(l).then((e=>{p(),0==e.code?n.length>1?f({delta:1}):_({url:"/pages/user/index"}):g({title:e.msg,icon:"none",duration:2e3})}))}})}}else g({title:"请同意用户协议",icon:"none",duration:2e3})},wechatLogin(){this.isAgree?this.turnUrl("/pages/user/wechatLogin"):g({title:"请同意用户协议",icon:"none",duration:2e3})},goBack(){_({url:"/pages/index/index"})},turnUrl(e="",t=1){e&&"#"!=e&&(1==t&&b({url:e}),2==t&&_({url:e}),3==t&&y({url:e}))}}},[["render",function(e,t,n,i,s,a){const o=r,c=w,d=$,h=E,g=A(C("uv-code"),R),m=A(C("uv-checkbox"),K),p=A(C("uv-checkbox-group"),G),f=A(C("uv-divider"),M),_=A(C("uv-icon"),j);return l(),u(o,{class:x(["content",s.theme]),style:T(s.backgroundStyle)},{default:k((()=>[L(o,{class:"wrap"},{default:k((()=>[L(o,{class:"status_bar",style:T({paddingTop:s.navHeight+"px"})},null,8,["style"]),L(o,{class:"login_top"},{default:k((()=>[L(o,null,{default:k((()=>[N("欢迎您，")])),_:1}),L(o,null,{default:k((()=>[N("亲爱的家人们！")])),_:1})])),_:1}),L(o,{class:"m_ctr"},{default:k((()=>[L(o,{class:"yuan_ctr"},{default:k((()=>[0==s.curLogin?(l(),u(h,{key:0,onSubmit:a.formSubmit},{default:k((()=>[L(o,{class:"input_title"},{default:k((()=>[N("账号：")])),_:1}),L(o,{class:"input_ctr"},{default:k((()=>[L(c,{name:"m_uid",placeholder:"请输入用户名/手机号"})])),_:1}),L(o,{class:"input_title"},{default:k((()=>[N("密码：")])),_:1}),L(o,{class:"input_ctr"},{default:k((()=>[L(c,{name:"m_pwd",password:"true",placeholder:"请输入密码"})])),_:1}),1==s.smsLogin?(l(),u(o,{key:0,class:"login_type",onClick:a.changeLogin},{default:k((()=>[N("手机验证码登录")])),_:1},8,["onClick"])):S("",!0),L(d,{class:"login_btn",formType:"submit"},{default:k((()=>[N("立即登录")])),_:1})])),_:1},8,["onSubmit"])):S("",!0),1==s.curLogin?(l(),u(h,{key:1,onSubmit:a.smsSubmit},{default:k((()=>[L(o,{class:"input_title"},{default:k((()=>[N("手机号：")])),_:1}),L(o,{class:"input_ctr"},{default:k((()=>[L(c,{name:"m_tel",type:"number",onInput:a.inputTel,placeholder:"请输入11位手机号"},null,8,["onInput"])])),_:1}),L(o,{class:"input_title"},{default:k((()=>[N("验证码：")])),_:1}),L(o,{class:"input_ctr"},{default:k((()=>[L(o,{class:"get_code_ctr"},{default:k((()=>[L(g,{seconds:s.seconds,onEnd:a.end,onStart:a.start,ref:"code",onChange:a.codeChange},null,8,["seconds","onEnd","onStart","onChange"]),L(o,{class:x(0==s.sending?"get_code_btn":"get_code_btn_on"),onClick:a.getCode},{default:k((()=>[N(D(s.tips),1)])),_:1},8,["class","onClick"])])),_:1}),L(c,{name:"m_code",type:"number",onInput:a.inputCode,placeholder:"请输入验证码"},null,8,["onInput"])])),_:1}),L(o,{class:"login_type",onClick:a.changeLogin},{default:k((()=>[N("账号登录")])),_:1},8,["onClick"]),L(d,{class:"login_btn",formType:"submit"},{default:k((()=>[N("立即登录")])),_:1})])),_:1},8,["onSubmit"])):S("",!0),L(o,{class:"agree_ctr"},{default:k((()=>[L(o,{class:"agree_ctt"},{default:k((()=>[L(p,{shape:"circle",size:"14",activeColor:s.themeColor,onChange:a.checkAgree},{default:k((()=>[L(m,{name:"agree"})])),_:1},8,["activeColor","onChange"]),L(o,null,{default:k((()=>[N("我已阅读并同意")])),_:1}),L(o,{onClick:t[0]||(t[0]=e=>a.turnUrl("/pages/article/agreement?type=user"))},{default:k((()=>[N("《用户协议》")])),_:1}),L(o,{onClick:t[1]||(t[1]=e=>a.turnUrl("/pages/article/agreement?type=privacy"))},{default:k((()=>[N("《隐私协议》")])),_:1})])),_:1})])),_:1}),1==s.otherLogin?(l(),u(o,{key:2,class:"m_ctr"},{default:k((()=>[L(f,{text:"第三方登录"}),L(o,{class:"other_login_ctr"},{default:k((()=>[L(o,{class:"other_login_btn",onClick:t[2]||(t[2]=e=>a.wechatLogin())},{default:k((()=>[L(_,{name:"wechat","custom-prefix":"custom-icon",color:"#fff",size:"26"})])),_:1})])),_:1})])),_:1})):S("",!0)])),_:1})])),_:1})])),_:1})])),_:1},8,["class","style"])}],["__scopeId","data-v-ab9cedc9"]]);export{q as default};