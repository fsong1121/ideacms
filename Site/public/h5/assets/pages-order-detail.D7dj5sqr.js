import{g as a,n as t,d as e,r as s,B as l,a as d,h as n,A as _,m as r,e as o,f as i,w as c,i as u,j as f,k as p,o as m,l as y,p as x,u as g,q as k,v as h,F as D,t as b,C,x as w}from"./index-CGszr9qB.js";import{_ as v}from"./uv-loading-page.DMunDfme.js";import{_ as O,r as U}from"./uni-app.es.CLj63pkI.js";import{_ as I}from"./uv-icon.40j6Cb1z.js";import{_ as $}from"./uv-count-down.DIUuJG1J.js";const j=O({data:()=>({theme:"",loading:!0,currentID:0,timeData:{},data:{}}),onLoad(a){let t=this;Reflect.ownKeys(a).length>0&&(t.currentID=a.hasOwnProperty("id")?a.id:0)},onShow(){let t=f(),e=a(),s={goods_id:0,url:e[e.length-1].route,platform:t.globalData.systemInfo.uniPlatform,model:t.globalData.systemInfo.deviceModel};this.$apis.visit.add(s),this.initData()},methods:{async initData(){let a,t=f(),e=this;await e.$onLaunched,e.theme=t.globalData.theme,a=await e.$apis.order.getData({id:e.currentID}),0==a.code?(1==a.data.order_state_no&&a.data.close_time<=0&&e.cancelOrder(a.data.id,2),e.data=a.data):e.data={},e.loading=!1},onChange(a){this.timeData=a},turnUrl(a="",l=1){a&&"#"!=a&&(1==l&&t({url:a}),2==l&&e({url:a}),3==l&&s({url:a}))},cancelOrder(a,t=1){let e=this;1==t?l({title:"提示",content:"确认要取消订单吗？",success(t){t.confirm&&(d({title:"加载中"}),e.$apis.order.cancelData({id:a}).then((a=>{n(),0==a.code?e.initData():_({title:a.msg,icon:"none"})})))}}):(d({title:"加载中"}),e.$apis.order.cancelData({id:a}).then((a=>{n(),0==a.code?e.initData():_({title:a.msg,icon:"none"})})))},delOrder(a){let e=this;l({title:"提示",content:"确认要删除订单吗？",success(s){s.confirm&&(d({title:"加载中"}),e.$apis.order.deleteData({id:a}).then((a=>{n(),0==a.code?t({url:"/pages/order/list"}):_({title:a.msg,icon:"none"})})))}})},receiptOrder(a){let t=this;l({title:"提示",content:"确认已收到货了吗？",success(e){e.confirm&&(d({title:"加载中"}),t.$apis.order.receiptData({id:a}).then((a=>{n(),0==a.code?t.initData():_({title:a.msg,icon:"none"})})))}})},onCall(){let a=f();r({phoneNumber:a.globalData.siteInfo.phone})}}},[["render",function(a,t,e,s,l,d){const n=U(o("uv-loading-page"),v),_=U(o("uv-icon"),I),r=p,f=U(o("uv-count-down"),$),O=w;return m(),i(r,{class:u(["content",l.theme])},{default:c((()=>[y(n,{loading:l.loading},null,8,["loading"]),l.loading?g("",!0):(m(),x(D,{key:0},[y(r,{class:"wrap"},{default:c((()=>[y(r,{class:"m_ctr"},{default:c((()=>[y(r,{class:"order_state_ctr"},{default:c((()=>[y(r,{class:"order_state_title"},{default:c((()=>["待付款"==l.data.order_state?(m(),i(_,{key:0,name:"underway-o","custom-prefix":"custom-icon",size:"36rpx",color:"#333",bold:!0})):g("",!0),"待发货"==l.data.order_state?(m(),i(_,{key:1,name:"send-gift-o","custom-prefix":"custom-icon",size:"36rpx",color:"#333",bold:!0})):g("",!0),"待收货"==l.data.order_state?(m(),i(_,{key:2,name:"logistics","custom-prefix":"custom-icon",size:"36rpx",color:"#333",bold:!0})):g("",!0),"已完成"==l.data.order_state?(m(),i(_,{key:3,name:"passed","custom-prefix":"custom-icon",size:"36rpx",color:"#333",bold:!0})):g("",!0),"已取消"==l.data.order_state?(m(),i(_,{key:4,name:"delete-o","custom-prefix":"custom-icon",size:"36rpx",color:"#333",bold:!0})):g("",!0),y(r,{class:"txt"},{default:c((()=>[k(h(l.data.order_state),1)])),_:1})])),_:1}),"待付款"==l.data.order_state?(m(),i(r,{key:0,class:"order_state_txt"},{default:c((()=>[y(r,null,{default:c((()=>[k("还剩")])),_:1}),y(f,{time:l.data.close_time,format:"HH:mm:ss",onChange:d.onChange,onFinish:t[0]||(t[0]=a=>d.cancelOrder(l.data.id,2))},{default:c((()=>[y(r,{class:"time"},{default:c((()=>[k(h(l.timeData.hours>=10?l.timeData.hours:"0"+l.timeData.hours)+":"+h(l.timeData.minutes>=10?l.timeData.minutes:"0"+l.timeData.minutes)+":"+h(l.timeData.seconds>=10?l.timeData.seconds:"0"+l.timeData.seconds),1)])),_:1})])),_:1},8,["time","onChange"]),y(r,null,{default:c((()=>[k("订单自动取消")])),_:1})])),_:1})):g("",!0)])),_:1}),y(r,{class:"yuan_ctr"},{default:c((()=>[y(r,{class:"order_address_ctr"},{default:c((()=>[y(r,{class:"order_address_ctt"},{default:c((()=>[y(r,null,{default:c((()=>[k(h(l.data.name)+" "+h(l.data.tel),1)])),_:1}),y(r,{class:"span"},{default:c((()=>[k(h(l.data.address),1)])),_:1})])),_:1})])),_:1})])),_:1}),y(r,{class:"yuan_ctr"},{default:c((()=>[(m(!0),x(D,null,b(l.data.goods_list,((a,t)=>(m(),i(r,{key:t,class:"order_goods_ctr",onClick:t=>d.turnUrl(a.goods_url)},{default:c((()=>[y(r,{class:"order_goods_pic"},{default:c((()=>[y(O,{mode:"widthFix",src:a.goods_pic},null,8,["src"])])),_:2},1024),y(r,{class:"order_goods_ctt"},{default:c((()=>[y(r,{class:"order_goods_title",style:C(""==a.spec_key_name?"-webkit-line-clamp:2;":"")},{default:c((()=>[k(h(a.goods_title),1)])),_:2},1032,["style"]),""!=a.spec_key_name?(m(),i(r,{key:0,class:"order_goods_subtitle"},{default:c((()=>[k(h(a.spec_key_name),1)])),_:2},1024)):g("",!0)])),_:2},1024),y(r,{class:"order_goods_price"},{default:c((()=>[y(r,null,{default:c((()=>[k("¥"+h(a.price),1)])),_:2},1024),y(r,null,{default:c((()=>[k("×"+h(a.amount),1)])),_:2},1024),l.data.order_state_no>1&&l.data.refund_state>0?(m(),i(r,{key:0,class:"span"},{default:c((()=>[k("退款中")])),_:1})):g("",!0)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1}),y(r,{class:"yuan_ctr"},{default:c((()=>[y(r,{class:"detail_txt"},{default:c((()=>[y(r,null,{default:c((()=>[k("订单类型")])),_:1}),y(r,{class:"span"},{default:c((()=>[k(h(l.data.order_type_title),1)])),_:1})])),_:1}),y(r,{class:"detail_txt"},{default:c((()=>[y(r,null,{default:c((()=>[k("订单编号")])),_:1}),y(r,{class:"span"},{default:c((()=>[k(h(l.data.order_sn),1)])),_:1})])),_:1}),y(r,{class:"detail_txt"},{default:c((()=>[y(r,null,{default:c((()=>[k("下单时间")])),_:1}),y(r,{class:"span"},{default:c((()=>[k(h(l.data.add_date),1)])),_:1})])),_:1}),y(r,{class:"detail_txt"},{default:c((()=>[y(r,null,{default:c((()=>[k("买家备注")])),_:1}),y(r,{class:"span"},{default:c((()=>[k(h(l.data.info),1)])),_:1})])),_:1})])),_:1}),l.data.order_state_no>1?(m(),i(r,{key:0,class:"yuan_ctr"},{default:c((()=>[y(r,{class:"detail_txt"},{default:c((()=>[y(r,null,{default:c((()=>[k("支付方式")])),_:1}),y(r,{class:"span"},{default:c((()=>[k(h(l.data.pay_type),1)])),_:1})])),_:1}),l.data.pay_date?(m(),i(r,{key:0,class:"detail_txt"},{default:c((()=>[y(r,null,{default:c((()=>[k("支付时间")])),_:1}),y(r,{class:"span"},{default:c((()=>[k(h(l.data.pay_date),1)])),_:1})])),_:1})):g("",!0)])),_:1})):g("",!0),l.data.order_state_no>2?(m(),i(r,{key:1,class:"yuan_ctr"},{default:c((()=>[y(r,{class:"detail_txt"},{default:c((()=>[y(r,null,{default:c((()=>[k("发货方式")])),_:1}),2==l.data.express_type?(m(),i(r,{key:0,class:"span"},{default:c((()=>[k("无需发货")])),_:1})):(m(),i(r,{key:1,class:"span"},{default:c((()=>[k(h(l.data.express_title)+"("+h(l.data.express_sn)+")",1)])),_:1}))])),_:1}),y(r,{class:"detail_txt"},{default:c((()=>[y(r,null,{default:c((()=>[k("发货时间")])),_:1}),y(r,{class:"span"},{default:c((()=>[k(h(l.data.express_date),1)])),_:1})])),_:1}),""!=l.data.express_info?(m(),i(r,{key:0,class:"detail_txt"},{default:c((()=>[y(r,null,{default:c((()=>[k("发货说明")])),_:1}),y(r,{class:"span"},{default:c((()=>[k(h(l.data.express_info),1)])),_:1})])),_:1})):g("",!0)])),_:1})):g("",!0),y(r,{class:"yuan_ctr"},{default:c((()=>[y(r,{class:"detail_txt1"},{default:c((()=>[y(r,null,{default:c((()=>[k("商品总计")])),_:1}),y(r,{class:"span"},{default:c((()=>[k("¥"+h(l.data.price),1)])),_:1})])),_:1}),y(r,{class:"detail_txt1"},{default:c((()=>[y(r,null,{default:c((()=>[k("运费")])),_:1}),y(r,{class:"span"},{default:c((()=>[k("¥"+h(l.data.express_price),1)])),_:1})])),_:1}),l.data.rebate_price>0?(m(),i(r,{key:0,class:"detail_txt1"},{default:c((()=>[y(r,null,{default:c((()=>[k("折扣")])),_:1}),y(r,{class:"span"},{default:c((()=>[k("-¥"+h(l.data.rebate_price),1)])),_:1})])),_:1})):g("",!0),l.data.discount_price>0?(m(),i(r,{key:1,class:"detail_txt1"},{default:c((()=>[y(r,null,{default:c((()=>[k("满减")])),_:1}),y(r,{class:"span"},{default:c((()=>[k("-¥"+h(l.data.discount_price),1)])),_:1})])),_:1})):g("",!0),l.data.coupon_price>0?(m(),i(r,{key:2,class:"detail_txt1"},{default:c((()=>[y(r,null,{default:c((()=>[k("优惠券")])),_:1}),y(r,{class:"span"},{default:c((()=>[k("-¥"+h(l.data.coupon_price),1)])),_:1})])),_:1})):g("",!0),l.data.exchange_price>0?(m(),i(r,{key:3,class:"detail_txt1"},{default:c((()=>[y(r,null,{default:c((()=>[k("积分抵扣")])),_:1}),y(r,{class:"span"},{default:c((()=>[k("-¥"+h(l.data.exchange_price),1)])),_:1})])),_:1})):g("",!0),y(r,{class:"detail_txt1"},{default:c((()=>[y(r),"integral"!=l.data.order_type?(m(),i(r,{key:0,class:"span"},{default:c((()=>[y(r,null,{default:c((()=>[k("实付款：")])),_:1}),y(r,{class:"red"},{default:c((()=>[k("¥"+h(l.data.pay_price),1)])),_:1})])),_:1})):(m(),i(r,{key:1,class:"span"},{default:c((()=>[y(r,null,{default:c((()=>[k("实付积分：")])),_:1}),y(r,{class:"red"},{default:c((()=>[k(h(l.data.exchange_integral),1)])),_:1})])),_:1}))])),_:1})])),_:1})])),_:1}),y(r,{class:"space_footer"})])),_:1}),y(r,{class:"footer"},{default:c((()=>[y(r,{class:"footer_ctt"},{default:c((()=>[y(r,{class:"detail_btn_ctr"},{default:c((()=>["已取消"==l.data.order_state?(m(),i(r,{key:0,class:"detail_btn",onClick:t[1]||(t[1]=a=>d.delOrder(l.data.id))},{default:c((()=>[k("删除订单")])),_:1})):g("",!0),"待付款"==l.data.order_state?(m(),x(D,{key:1},[y(r,{class:"detail_btn",onClick:t[2]||(t[2]=a=>d.cancelOrder(l.data.id))},{default:c((()=>[k("取消订单")])),_:1}),y(r,{class:"detail_btn_on",onClick:t[3]||(t[3]=a=>d.turnUrl("/pages/pay/index?sn="+l.data.order_sn))},{default:c((()=>[k("去付款")])),_:1})],64)):g("",!0),"待发货"==l.data.order_state?(m(),x(D,{key:2},[y(r,{class:"detail_btn"},{default:c((()=>[y(r,{onClick:d.onCall},{default:c((()=>[k("提醒发货")])),_:1},8,["onClick"])])),_:1}),1==l.data.is_refund&&0==l.data.refund_state?(m(),i(r,{key:0,class:"detail_btn_on",onClick:t[4]||(t[4]=a=>d.turnUrl("/pages/refund/index?id="+l.data.id))},{default:c((()=>[k("申请退款")])),_:1})):g("",!0)],64)):g("",!0),"待收货"==l.data.order_state?(m(),x(D,{key:3},[1==l.data.is_refund&&0==l.data.refund_state?(m(),i(r,{key:0,class:"detail_btn",onClick:t[5]||(t[5]=a=>d.turnUrl("/pages/refund/index?id="+l.data.id))},{default:c((()=>[k("退换货")])),_:1})):g("",!0),1==l.data.express_type&&""!=l.data.express_sn?(m(),i(r,{key:1,class:"detail_btn",onClick:t[6]||(t[6]=a=>d.turnUrl("/pages/order/express?id="+l.data.id))},{default:c((()=>[k("查看物流")])),_:1})):g("",!0),y(r,{class:"detail_btn_on",onClick:t[7]||(t[7]=a=>d.receiptOrder(l.data.id))},{default:c((()=>[k("确认收货")])),_:1})],64)):g("",!0),"已完成"==l.data.order_state?(m(),x(D,{key:4},[1==l.data.is_refund&&0==l.data.refund_state?(m(),i(r,{key:0,class:"detail_btn",onClick:t[8]||(t[8]=a=>d.turnUrl("/pages/refund/index?id="+l.data.id))},{default:c((()=>[k("退换货")])),_:1})):g("",!0),1==l.data.express_type&&""!=l.data.express_sn?(m(),i(r,{key:1,class:"detail_btn",onClick:t[9]||(t[9]=a=>d.turnUrl("/pages/order/express?id="+l.data.id))},{default:c((()=>[k("查看物流")])),_:1})):g("",!0)],64)):g("",!0)])),_:1})])),_:1})])),_:1})],64))])),_:1},8,["class"])}],["__scopeId","data-v-60eef4f5"]]);export{j as default};