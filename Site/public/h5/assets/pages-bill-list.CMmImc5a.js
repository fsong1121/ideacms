import{g as t,a,h as e,c as l,n as s,d as i,r as o,e as n,f as r,w as d,i as c,j as _,k as u,o as f,l as g,p as b,F as m,t as p,u as h,q as y,v as k,C as v,x as C}from"./index-CGszr9qB.js";import{_ as L}from"./uv-loading-page.DMunDfme.js";import{_ as w,r as j}from"./uni-app.es.CLj63pkI.js";import{_ as x}from"./uv-tabs.BBaBSH8a.js";import{_ as D}from"./uv-empty.BfD1VQnC.js";import"./uv-badge.C6BqFgsL.js";import"./uv-icon.40j6Cb1z.js";const T=w({data:()=>({theme:"",loading:!0,tabsTitle:[{name:"开票订单"},{name:"开票历史"}],currentTab:0,lineColor:"",dataList:[],billList:[]}),onLoad(t){let a=this;Reflect.ownKeys(t).length>0&&t.hasOwnProperty("state")&&(a.currentTab=parseInt(t.state))},onShow(){let a=_(),e=t(),l={goods_id:0,url:e[e.length-1].route,platform:a.globalData.systemInfo.uniPlatform,model:a.globalData.systemInfo.deviceModel};this.$apis.visit.add(l),this.initData()},onPullDownRefresh(){a({title:"加载中"}),this.initData("refresh")},methods:{async initData(t=""){let a,s=_(),i=this;await i.$onLaunched,i.theme=s.globalData.theme,i.lineColor=s.globalData.themeColor,a=await i.$apis.bill.getOrderList({size:1e3}),i.dataList=a.data,a=await i.$apis.bill.getList({size:1e3}),i.billList=a.data,"refresh"==t&&(e(),l()),i.loading=!1},tabChange(t){this.currentTab=t.index},turnUrl(t="",a=1){t&&"#"!=t&&(1==a&&s({url:t}),2==a&&i({url:t}),3==a&&o({url:t}))}}},[["render",function(t,a,e,l,s,i){const o=j(n("uv-loading-page"),L),_=j(n("uv-tabs"),x),w=u,T=C,I=j(n("uv-empty"),D);return f(),r(w,{class:c(["content",s.theme])},{default:d((()=>[g(o,{loading:s.loading},null,8,["loading"]),s.loading?h("",!0):(f(),b(m,{key:0},[g(w,{class:"header"},{default:d((()=>[g(w,{class:"header_ctt"},{default:d((()=>[g(_,{list:s.tabsTitle,current:s.currentTab,inactiveStyle:{color:"#999",transform:"scale(1)"},activeStyle:{color:"#333",transform:"scale(1.05)",fontWeight:"bold"},lineColor:s.lineColor,scrollable:!1,onChange:i.tabChange},null,8,["list","current","inactiveStyle","activeStyle","lineColor","onChange"])])),_:1})])),_:1}),g(w,{class:"wrap"},{default:d((()=>[g(w,{class:"space_header"}),g(w,{class:"m_ctr"},{default:d((()=>[g(w,{class:"space_ctr"}),0==s.currentTab?(f(),b(m,{key:0},[(f(!0),b(m,null,p(s.dataList,((t,a)=>(f(),r(w,{class:"yuan_ctr",key:a},{default:d((()=>[g(w,{class:"order_list_title"},{default:d((()=>[g(w,null,{default:d((()=>[y("订单号："+k(t.order_sn),1)])),_:2},1024),g(w,{class:"title_btn",onClick:a=>i.turnUrl("/pages/bill/index?id="+t.id)},{default:d((()=>[y("去开票")])),_:2},1032,["onClick"])])),_:2},1024),(f(!0),b(m,null,p(t.goods_list,((t,a)=>(f(),r(w,{class:"order_goods_ctr",key:a},{default:d((()=>[g(w,{class:"order_goods_pic"},{default:d((()=>[g(T,{mode:"widthFix",src:t.goods_pic},null,8,["src"])])),_:2},1024),g(w,{class:"order_goods_ctt"},{default:d((()=>[g(w,{class:"order_goods_title",style:v(""==t.spec_key_name?"-webkit-line-clamp:2;":"")},{default:d((()=>[y(k(t.goods_title),1)])),_:2},1032,["style"]),""!=t.spec_key_name?(f(),r(w,{key:0,class:"order_goods_subtitle"},{default:d((()=>[y(k(t.spec_key_name),1)])),_:2},1024)):h("",!0)])),_:2},1024),g(w,{class:"order_goods_price"},{default:d((()=>[g(w,null,{default:d((()=>[y("¥"+k(t.price),1)])),_:2},1024),g(w,null,{default:d((()=>[y("×"+k(t.amount),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128))])),_:2},1024)))),128)),s.dataList.length<1?(f(),r(w,{key:0,class:"none_ctr"},{default:d((()=>[g(I,{icon:"/static/images/none.png",text:"暂无开票订单"})])),_:1})):h("",!0)],64)):(f(),b(m,{key:1},[(f(!0),b(m,null,p(s.billList,((t,a)=>(f(),r(w,{class:"yuan_ctr",key:a},{default:d((()=>[g(w,{class:"bill_list_ctr",onClick:a=>i.turnUrl("/pages/bill/detail?id="+t.id)},{default:d((()=>[g(w,{class:"bill_list_ctt"},{default:d((()=>[g(w,null,{default:d((()=>[y("开票编号："+k(t.sn),1)])),_:2},1024),g(w,null,{default:d((()=>[y("开票金额："+k(t.fee)+"元",1)])),_:2},1024)])),_:2},1024),1==t.state?(f(),r(w,{key:0,class:"bill_list_btn"},{default:d((()=>[y("待开票")])),_:1})):h("",!0),2==t.state?(f(),r(w,{key:1,class:"bill_list_btn_yes"},{default:d((()=>[y("已开票")])),_:1})):h("",!0),3==t.state?(f(),r(w,{key:2,class:"bill_list_btn_no"},{default:d((()=>[y("已驳回")])),_:1})):h("",!0)])),_:2},1032,["onClick"])])),_:2},1024)))),128)),s.billList.length<1?(f(),r(w,{key:0,class:"none_ctr"},{default:d((()=>[g(I,{icon:"/static/images/none.png",text:"暂无开票记录"})])),_:1})):h("",!0)],64))])),_:1})])),_:1})],64))])),_:1},8,["class"])}],["__scopeId","data-v-e12999b0"]]);export{T as default};
