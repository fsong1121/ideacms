import{g as t,h as a,X as l,n as e,d as s,r as i,B as u,e as d,o as n,f as c,w as _,i as o,j as r,k as f,l as p,q as m,v as g,u as h,p as y,F as k}from"./index-C4nhL_Jx.js";import{_ as v}from"./uv-loading-page.PtN9WcWT.js";import{_ as w,r as D}from"./uni-app.es.pQKBppRq.js";import{_ as b}from"./uv-icon.Nv3ytMSE.js";import{_ as x}from"./uv-empty.CTmrlToS.js";const I=w({data:()=>({theme:"",loading:!0,currentID:0,data:{},picUrl:[],isEffective:0}),onLoad(t){let a=this;Reflect.ownKeys(t).length>0&&t.hasOwnProperty("id")&&(a.currentID=t.id)},onShow(){let a=r(),l=t(),e={goods_id:0,url:l[l.length-1].route,platform:a.globalData.systemInfo.uniPlatform,model:a.globalData.systemInfo.deviceModel};this.$apis.visit.add(e),this.initData()},methods:{async initData(){let t,l=r(),e=this;if(await e.$onLaunched,e.theme=l.globalData.theme,t=await e.$apis.bill.getData({id:e.currentID}),0==t.code){let a=[];a[0]=t.data.pic,e.isEffective=1,e.data=t.data,e.picUrl=a}a(),e.loading=!1},showPic(){l({urls:this.picUrl,current:0})},turnUrl(t="",a=1){t&&"#"!=t&&(1==a&&e({url:t}),2==a&&s({url:t}),3==a&&i({url:t}))},delBill(){let t=this;u({title:"提示",content:"确认要删除吗？",success(a){a.confirm&&t.$apis.bill.deleteData({id:t.currentID}).then((t=>{0==t.code?i({url:"/pages/bill/list"}):console.log(t.msg)}))}})}}},[["render",function(t,a,l,e,s,i){const u=D(d("uv-loading-page"),v),r=f,w=D(d("uv-icon"),b),I=D(d("uv-empty"),x);return n(),c(r,{class:o(["content",s.theme])},{default:_((()=>[p(u,{loading:s.loading},null,8,["loading"]),s.loading?h("",!0):(n(),c(r,{key:0,class:"wrap"},{default:_((()=>[p(r,{class:"m_ctr"},{default:_((()=>[p(r,{class:"space_ctr"}),1==s.isEffective?(n(),c(r,{key:0},{default:_((()=>[p(r,{class:"yuan_ctr"},{default:_((()=>[p(r,{class:"input_ctr"},{default:_((()=>[p(r,{class:"input_title"},{default:_((()=>[m("发票类型：")])),_:1}),p(r,null,{default:_((()=>[m(g(s.data.type),1)])),_:1})])),_:1}),p(r,{class:"input_ctr"},{default:_((()=>[p(r,{class:"input_title"},{default:_((()=>[m("发票抬头：")])),_:1}),p(r,null,{default:_((()=>[m(g(s.data.tax_title),1)])),_:1})])),_:1}),""!=s.data.tax_sn?(n(),c(r,{key:0,class:"input_ctr"},{default:_((()=>[p(r,{class:"input_title"},{default:_((()=>[m("税号：")])),_:1}),p(r,null,{default:_((()=>[m(g(s.data.tax_sn),1)])),_:1})])),_:1})):h("",!0),p(r,{class:"input_ctr"},{default:_((()=>[p(r,{class:"input_title"},{default:_((()=>[m("开票金额：")])),_:1}),p(r,null,{default:_((()=>[m(g(s.data.fee)+"元",1)])),_:1})])),_:1}),p(r,{class:"input_ctr"},{default:_((()=>[p(r,{class:"input_title"},{default:_((()=>[m("开票备注：")])),_:1}),p(r,null,{default:_((()=>[m(g(s.data.info),1)])),_:1})])),_:1}),p(r,{class:"input_ctr"},{default:_((()=>[p(r,{class:"input_title"},{default:_((()=>[m("开票状态：")])),_:1}),1==s.data.state?(n(),c(r,{key:0},{default:_((()=>[m("待开票")])),_:1})):h("",!0),2==s.data.state?(n(),c(r,{key:1},{default:_((()=>[m("已开票")])),_:1})):h("",!0),3==s.data.state?(n(),c(r,{key:2},{default:_((()=>[m("已驳回")])),_:1})):h("",!0)])),_:1}),p(r,{class:"input_ctr",onClick:a[0]||(a[0]=t=>i.turnUrl("/pages/order/detail?id="+s.data.order_id))},{default:_((()=>[p(r,{class:"input_title"},{default:_((()=>[m("所属订单：")])),_:1}),p(r,null,{default:_((()=>[m(g(s.data.order_sn),1)])),_:1}),p(r,{class:"arrow"},{default:_((()=>[p(w,{name:"arrow","custom-prefix":"custom-icon",color:"#999"})])),_:1})])),_:1}),2==s.data.state?(n(),y(k,{key:1},[p(r,{class:"input_ctr",onClick:a[1]||(a[1]=t=>i.showPic())},{default:_((()=>[p(r,{class:"input_title"},{default:_((()=>[m("发票编号：")])),_:1}),p(r,null,{default:_((()=>[m(g(s.data.tax_no),1)])),_:1}),p(r,{class:"arrow"},{default:_((()=>[p(w,{name:"arrow","custom-prefix":"custom-icon",color:"#999"})])),_:1})])),_:1}),p(r,{class:"input_ctr"},{default:_((()=>[p(r,{class:"input_title"},{default:_((()=>[m("操作时间：")])),_:1}),p(r,null,{default:_((()=>[m(g(s.data.opt_date),1)])),_:1})])),_:1})],64)):h("",!0),3==s.data.state?(n(),y(k,{key:2},[p(r,{class:"input_ctr"},{default:_((()=>[p(r,{class:"input_title"},{default:_((()=>[m("驳回原因：")])),_:1}),p(r,null,{default:_((()=>[m(g(s.data.reason),1)])),_:1})])),_:1}),p(r,{class:"input_ctr"},{default:_((()=>[p(r,{class:"input_title"},{default:_((()=>[m("操作时间：")])),_:1}),p(r,null,{default:_((()=>[m(g(s.data.opt_date),1)])),_:1})])),_:1})],64)):h("",!0)])),_:1}),3==s.data.state?(n(),c(r,{key:0,class:"bill_btn",onClick:i.delBill},{default:_((()=>[m("删除")])),_:1},8,["onClick"])):h("",!0)])),_:1})):(n(),c(r,{key:1,class:"none_ctr"},{default:_((()=>[p(I,{icon:"/static/images/none.png",text:"开票信息不存在"})])),_:1}))])),_:1})])),_:1}))])),_:1},8,["class"])}],["__scopeId","data-v-149e463b"]]);export{I as default};