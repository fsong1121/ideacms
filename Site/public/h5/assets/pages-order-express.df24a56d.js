import{g as a,A as s,Z as e,m as t,o as l,e as o,w as d,f as r,i as c,j as u,k as i,p as n,u as p,t as _,l as f,q as x,F as m,v as y}from"./index-61bca09a.js";import{_ as D}from"./uv-loading-page.a3cea9e4.js";import{_ as g,r as h}from"./uni-app.es.c541313a.js";import{_ as k}from"./uv-icon.06d9cba4.js";const v=g({data:()=>({theme:"",loading:!0,currentID:0,detail:{},expressState:0,expressData:{}}),onLoad(a){let s=this;Reflect.ownKeys(a).length>0&&a.hasOwnProperty("id")&&(s.currentID=a.id)},onShow(){let s=c(),e=a(),t={goods_id:0,url:e[e.length-1].route,platform:s.globalData.systemInfo.uniPlatform,model:s.globalData.systemInfo.deviceModel};this.$apis.visit.add(t),this.initData()},methods:{async initData(){let a,e=c(),t=this;await t.$onLaunched,t.theme=e.globalData.theme,a=await t.$apis.order.getData({id:t.currentID}),0==a.code&&(a.data.order_state_no>2&&""!=a.data.express_sn&&t.$apis.order.getExpress({express_sn:a.data.express_sn,tel:a.data.tel}).then((a=>{0==a.code?(t.expressData=a.data,t.expressState=1):s({title:a.message,icon:"none"})})),t.detail=a.data),t.loading=!1},copy(){e({data:this.expressData.mailNo})},call(){t({phoneNumber:this.expressData.tel})}}},[["render",function(a,s,e,t,c,g){const v=h(y("uv-loading-page"),D),w=u,b=h(y("uv-icon"),k);return l(),o(w,{class:r(["content",c.theme])},{default:d((()=>[i(v,{loading:c.loading},null,8,["loading"]),c.loading?_("",!0):(l(),o(w,{key:0,class:"wrap"},{default:d((()=>[i(w,{class:"m_ctr"},{default:d((()=>[i(w,{class:"space_ctr"}),i(w,{class:"yuan_ctr"},{default:d((()=>[1==c.expressState?(l(),o(w,{key:0,class:"top_ctr"},{default:d((()=>[i(w,{class:"top_ico"},{default:d((()=>[i(b,{name:"deliver-fill","custom-prefix":"custom-icon",color:"#8687aa",size:"20rpx"})])),_:1}),i(w,{class:"top_txt"},{default:d((()=>[n(p(c.expressData.expTextName)+" "+p(c.expressData.mailNo),1)])),_:1}),i(w,{class:"top_right"},{default:d((()=>[i(w,{class:"top_copy",onClick:g.copy},{default:d((()=>[n("复制")])),_:1},8,["onClick"]),i(w,{class:"top_tel",onClick:g.call},{default:d((()=>[i(b,{name:"tel","custom-prefix":"custom-icon",color:"#333",size:"24rpx"})])),_:1},8,["onClick"])])),_:1})])),_:1})):_("",!0),i(w,{class:"express_ctr"},{default:d((()=>[i(w,{class:"express_left"},{default:d((()=>[i(w,{class:"line"})])),_:1}),i(w,{class:"express_right"},{default:d((()=>[i(w,{class:"express_ctt"},{default:d((()=>[i(w,{class:"txt"},{default:d((()=>[i(w,{class:"yuan"}),n(" 收货地址："+p(c.detail.address),1)])),_:1})])),_:1}),(l(!0),f(m,null,x(c.expressData.data,((a,s)=>(l(),o(w,{class:"express_ctt",key:s},{default:d((()=>[0==s?(l(),f(m,{key:0},[i(w,{class:"title on"},{default:d((()=>[i(w,{class:"yuan"},{default:d((()=>[i(b,{name:"deliver","custom-prefix":"custom-icon",color:"#fff",size:"20rpx"})])),_:1}),1==c.expressData.status?(l(),o(w,{key:0},{default:d((()=>[n("暂无记录")])),_:1})):_("",!0),2==c.expressData.status?(l(),o(w,{key:1},{default:d((()=>[n("在途中")])),_:1})):_("",!0),3==c.expressData.status?(l(),o(w,{key:2},{default:d((()=>[n("派送中")])),_:1})):_("",!0),4==c.expressData.status?(l(),o(w,{key:3},{default:d((()=>[n("已签收")])),_:1})):_("",!0),5==c.expressData.status?(l(),o(w,{key:4},{default:d((()=>[n("用户拒签")])),_:1})):_("",!0),6==c.expressData.status?(l(),o(w,{key:5},{default:d((()=>[n("疑难件")])),_:1})):_("",!0),7==c.expressData.status?(l(),o(w,{key:6},{default:d((()=>[n("无效单")])),_:1})):_("",!0),8==c.expressData.status?(l(),o(w,{key:7},{default:d((()=>[n("超时单")])),_:1})):_("",!0),9==c.expressData.status?(l(),o(w,{key:8},{default:d((()=>[n("签收失败")])),_:1})):_("",!0),10==c.expressData.status?(l(),o(w,{key:9},{default:d((()=>[n("退回")])),_:1})):_("",!0)])),_:1}),i(w,{class:"txt on"},{default:d((()=>[n(p(a.context),1)])),_:2},1024)],64)):(l(),o(w,{key:1,class:"txt"},{default:d((()=>[i(w,{class:"yuan"}),n(" "+p(a.context),1)])),_:2},1024)),i(w,{class:"txt"},{default:d((()=>[n(p(a.time),1)])),_:2},1024)])),_:2},1024)))),128)),i(w,{class:"express_ctt"},{default:d((()=>[i(w,{class:"title"},{default:d((()=>[i(w,{class:"yuan"},{default:d((()=>[i(b,{name:"orders-o","custom-prefix":"custom-icon",color:"#fff",size:"20rpx"})])),_:1}),n(" 已下单 ")])),_:1}),i(w,{class:"txt"},{default:d((()=>[n(" 您提交了订单，请等待商家确认发货 ")])),_:1}),i(w,{class:"txt"},{default:d((()=>[n(p(c.detail.add_date),1)])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1}))])),_:1},8,["class"])}],["__scopeId","data-v-88b1db5a"]]);export{v as default};