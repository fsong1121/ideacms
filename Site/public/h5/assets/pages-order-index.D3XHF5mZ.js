import{g as e,E as a,n as s,A as d,a as o,h as t,r as l,e as r,f as i,w as c,i as n,j as _,k as u,o as g,l as A,p,q as f,v as y,F as h,t as I,u as m,x as v,I as P,C}from"./index-qqngRkUa.js";import{_ as k}from"./uv-loading-page.B-dgjlQB.js";import{_ as E,r as K}from"./uni-app.es.DMzxE133.js";import{_ as j}from"./uv-icon.BH_bzOnD.js";import{_ as x,a as O}from"./uv-checkbox-group.CE1ooxHf.js";const Y=E({data:()=>({theme:"",themeColor:"",loading:!0,address:{name:"收货信息为空",tel:"",province:"",address:"点击去添加一个收货地址"},buyType:0,orderType:"",activityId:0,groupId:0,goodsId:0,specKey:"",amount:0,cartIds:"",info:"",sendType:1,isExchange:0,data:{goodsList:[],goodsPrice:"0.00",sendPrice:"0.00",rebatePrice:"0.00",discountPrice:"0.00",couponPrice:"0.00",exchangeIntegral:"0",exchangePrice:"0.00",payPrice:"0.00"},tmplIds:[]}),onLoad(e){let a=this;if(Reflect.ownKeys(e).length>0)if(e.hasOwnProperty("goods_id")){let s=e.hasOwnProperty("order_type")?e.order_type:"",d=e.hasOwnProperty("activity_id")?e.activity_id:0,o=e.hasOwnProperty("group_id")?e.group_id:0;a.buyType=0,a.orderType=s,a.activityId=d,a.groupId=o,a.goodsId=e.goods_id,a.specKey=decodeURIComponent(e.spec_key),a.amount=e.amount}else{let s=e.cart_id;a.buyType=1,a.cartIds=s}},onShow(){let a=_(),s=e(),d={goods_id:0,url:s[s.length-1].route,platform:a.globalData.systemInfo.uniPlatform,model:a.globalData.systemInfo.deviceModel};this.$apis.visit.add(d),this.initData()},methods:{async initData(){let e,s=_(),d=this;await d.$onLaunched,d.theme=s.globalData.theme,d.themeColor=s.globalData.themeColor;let o=[];e=await d.$apis.config.getMessage(),0==e.code&&(1==e.data.miniapp_user_pay&&""!=e.data.miniapp_user_pay_id&&o.push(e.data.miniapp_user_pay_id),1==e.data.miniapp_user_send&&""!=e.data.miniapp_user_send_id&&o.push(e.data.miniapp_user_send_id)),d.tmplIds=o,e=await d.$apis.address.getData({id:a("addressId")||0}),0==e.code?(d.address.name=e.data.name,d.address.tel=e.data.tel,d.address.province=e.data.province,d.address.address=e.data.province+e.data.city+e.data.county+e.data.address):(d.address.name="收货信息为空",d.address.tel="",d.address.province="",d.address.address="点击去添加一个收货地址"),e=await d.$apis.order.fillData({buy_type:d.buyType,order_type:d.orderType,activity_id:d.activityId,goods_id:d.goodsId,spec_key:d.specKey,amount:d.amount,cart_id:d.cartIds,province:d.address.province}),0==e.code?d.data=e.data:(d.data.goodsList=[],d.data.goodsPrice="0.00",d.data.sendPrice="0.00",d.data.rebatePrice="0.00",d.data.discountPrice="0.00",d.data.couponPrice="0.00",d.data.exchangeIntegral="0",d.data.exchangePrice="0.00",d.data.payPrice="0.00"),d.loading=!1},checkExchange(e){this.isExchange=e.length},selectAddress(){s({url:"/pages/user/address"})},inputTyping(e){this.info=e.detail.value},onSubmit(){let e=_(),s=this,r=2;r=1,e.isWechat()&&(r=3),uni.$uv.throttle((function(){if(s.data.goodsList.length>0){if(""==s.address.tel)return void d({title:"收货信息为空",icon:"error"});let e={buy_type:s.buyType,order_type:s.orderType,activity_id:s.activityId,group_id:s.groupId,goods_id:s.goodsId,spec_key:s.specKey,amount:s.amount,cart_id:s.cartIds,name:s.address.name,tel:s.address.tel,province:s.address.province,address:s.address.address,send_type:s.sendType,info:s.info,terminal:r,is_exchange:s.isExchange,form_token:a("formToken")||""};o({title:"提交中"}),s.$apis.order.saveData(e).then((e=>{t(),0==e.code?0==e.is_pay?l({url:"/pages/pay/index?sn="+e.sn}):l({url:"/pages/order/list"}):d({title:e.msg,icon:"none"})}))}else d({title:"商品为空",icon:"error"})}))}}},[["render",function(e,a,s,d,o,t){const l=K(r("uv-loading-page"),k),_=u,E=K(r("uv-icon"),j),Y=v,J=P,S=K(r("uv-checkbox"),x),Q=K(r("uv-checkbox-group"),O);return g(),i(_,{class:n(["content",o.theme])},{default:c((()=>[A(l,{loading:o.loading},null,8,["loading"]),o.loading?m("",!0):(g(),p(h,{key:0},[A(_,{class:"wrap"},{default:c((()=>[A(_,{class:"m_ctr"},{default:c((()=>[A(_,{class:"space_ctr"}),A(_,{class:"yuan_ctr",style:{position:"relative",overflow:"hidden"}},{default:c((()=>[A(_,{class:"order_address_ctr",onClick:t.selectAddress},{default:c((()=>[A(_,{class:"order_address_ctt"},{default:c((()=>[A(_,null,{default:c((()=>[f(y(o.address.name)+" "+y(o.address.tel),1)])),_:1}),A(_,{class:"span"},{default:c((()=>[f(y(o.address.address),1)])),_:1})])),_:1}),A(_,{class:"order_address_right"},{default:c((()=>[A(E,{name:"arrow","custom-prefix":"custom-icon",size:"40rpx",color:"#ddd"})])),_:1}),A(_,{class:"order_address_foot"},{default:c((()=>[A(Y,{mode:"widthFix",src:"data:image/gif;base64,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"})])),_:1})])),_:1},8,["onClick"])])),_:1}),A(_,{class:"yuan_ctr"},{default:c((()=>[(g(!0),p(h,null,I(o.data.goodsList,((e,a)=>(g(),i(_,{class:"order_goods_ctr",key:a},{default:c((()=>[A(_,{class:"order_goods_pic"},{default:c((()=>[A(Y,{mode:"widthFix",src:e.pic},null,8,["src"])])),_:2},1024),A(_,{class:"order_goods_ctt"},{default:c((()=>[A(_,{class:"order_goods_title",style:C(""==e.spec_key_name?"-webkit-line-clamp:2;":"")},{default:c((()=>[f(y(e.title),1)])),_:2},1032,["style"]),""!=e.spec_key_name?(g(),i(_,{key:0,class:"order_goods_subtitle"},{default:c((()=>[f(y(e.spec_key_name),1)])),_:2},1024)):m("",!0),"integral"!=o.orderType?(g(),i(_,{key:1,class:"order_goods_price"},{default:c((()=>[A(_,null,{default:c((()=>[f("¥"+y(e.price),1)])),_:2},1024),A(_,{class:"span"},{default:c((()=>[f("×"+y(e.amount),1)])),_:2},1024)])),_:2},1024)):(g(),i(_,{key:2,class:"order_goods_price"},{default:c((()=>[A(_,{class:"price"},{default:c((()=>[A(_,null,{default:c((()=>[f(y(e.goods_integral),1)])),_:2},1024),A(_,{class:"span2"},{default:c((()=>[f("积分")])),_:1})])),_:2},1024),A(_,{class:"span"},{default:c((()=>[f("×"+y(e.amount),1)])),_:2},1024)])),_:2},1024))])),_:2},1024)])),_:2},1024)))),128)),A(_,{class:"order_goods_cell"},{default:c((()=>[A(_,{class:"order_goods_cell_title"},{default:c((()=>[f("备注")])),_:1}),A(_,{class:"order_goods_cell_info"},{default:c((()=>[A(J,{name:"m_info",onInput:t.inputTyping,placeholder:"建议留言前先和商家沟通确认"},null,8,["onInput"])])),_:1})])),_:1}),A(_,{class:"order_goods_cell"},{default:c((()=>[A(_,{class:"order_goods_cell_title"},{default:c((()=>[f("配送")])),_:1}),A(_,{class:"order_goods_cell_info"},{default:c((()=>[A(_,null,{default:c((()=>[f("快递运输")])),_:1}),A(_,{style:{"margin-left":"8rpx"}},{default:c((()=>[A(E,{name:"arrow","custom-prefix":"custom-icon",size:"24rpx",color:"#999"})])),_:1})])),_:1})])),_:1})])),_:1}),"integral"!=o.orderType&&o.data.exchangeIntegral>0?(g(),i(_,{key:0,class:"yuan_ctr"},{default:c((()=>[A(_,{class:"order_goods_cell"},{default:c((()=>[A(_,{class:"order_goods_cell_title",style:{width:"152rpx"}},{default:c((()=>[A(Q,{shape:"circle",size:"14",activeColor:o.themeColor,onChange:t.checkExchange},{default:c((()=>[A(S,{name:"exchange"})])),_:1},8,["activeColor","onChange"]),A(_,null,{default:c((()=>[f("积分抵扣")])),_:1})])),_:1}),A(_,{class:"order_goods_cell_info",style:{color:"#999"}},{default:c((()=>[f("使用"+y(o.data.exchangeIntegral)+"积分抵扣¥"+y(o.data.exchangePrice)+"元",1)])),_:1})])),_:1})])),_:1})):m("",!0),A(_,{class:"yuan_ctr"},{default:c((()=>[A(_,{class:"order_goods_cell"},{default:c((()=>[A(_,{class:"order_goods_cell_title"},{default:c((()=>[f("商品金额")])),_:1}),A(_,{class:"order_goods_cell_info"},{default:c((()=>[A(_,{class:"price"},{default:c((()=>[f("¥"+y(o.data.goodsPrice),1)])),_:1})])),_:1})])),_:1}),A(_,{class:"order_goods_cell"},{default:c((()=>[A(_,{class:"order_goods_cell_title"},{default:c((()=>[f("运费")])),_:1}),A(_,{class:"order_goods_cell_info"},{default:c((()=>[A(_,{class:"price"},{default:c((()=>[f("¥"+y(o.data.sendPrice),1)])),_:1})])),_:1})])),_:1}),o.data.rebatePrice>0?(g(),i(_,{key:0,class:"order_goods_cell"},{default:c((()=>[A(_,{class:"order_goods_cell_title"},{default:c((()=>[f("折扣")])),_:1}),A(_,{class:"order_goods_cell_info"},{default:c((()=>[A(_,{class:"price"},{default:c((()=>[f("-¥"+y(o.data.rebatePrice),1)])),_:1})])),_:1})])),_:1})):m("",!0),o.data.discountPrice>0?(g(),i(_,{key:1,class:"order_goods_cell"},{default:c((()=>[A(_,{class:"order_goods_cell_title"},{default:c((()=>[f("满减")])),_:1}),A(_,{class:"order_goods_cell_info"},{default:c((()=>[A(_,{class:"price"},{default:c((()=>[f("-¥"+y(o.data.discountPrice),1)])),_:1})])),_:1})])),_:1})):m("",!0),o.data.couponPrice>0?(g(),i(_,{key:2,class:"order_goods_cell"},{default:c((()=>[A(_,{class:"order_goods_cell_title"},{default:c((()=>[f("优惠券")])),_:1}),A(_,{class:"order_goods_cell_info"},{default:c((()=>[A(_,{class:"price"},{default:c((()=>[f("-¥"+y(o.data.couponPrice),1)])),_:1})])),_:1})])),_:1})):m("",!0),"integral"==o.orderType&&o.data.exchangeIntegral>0?(g(),i(_,{key:3,class:"order_goods_cell"},{default:c((()=>[A(_,{class:"order_goods_cell_title"},{default:c((()=>[f("积分抵扣")])),_:1}),A(_,{class:"order_goods_cell_info"},{default:c((()=>[A(_,{class:"price"},{default:c((()=>[f("-¥"+y(o.data.goodsPrice),1)])),_:1})])),_:1})])),_:1})):m("",!0),"integral"!=o.orderType&&o.data.exchangeIntegral>0&&o.isExchange>0?(g(),i(_,{key:4,class:"order_goods_cell"},{default:c((()=>[A(_,{class:"order_goods_cell_title"},{default:c((()=>[f("积分抵扣")])),_:1}),A(_,{class:"order_goods_cell_info"},{default:c((()=>[A(_,{class:"price"},{default:c((()=>[f("-¥"+y(o.data.exchangePrice),1)])),_:1})])),_:1})])),_:1})):m("",!0)])),_:1})])),_:1}),A(_,{class:"space_footer"})])),_:1}),A(_,{class:"footer"},{default:c((()=>[A(_,{class:"footer_ctt"},{default:c((()=>[A(_,{class:"order_price"},{default:c((()=>[A(_,{class:"price_title"},{default:c((()=>[f("合计：")])),_:1}),"integral"!=o.orderType?(g(),p(h,{key:0},[A(_,{class:"price_fh"},{default:c((()=>[f("¥")])),_:1}),0==o.isExchange?(g(),i(_,{key:0,class:"price"},{default:c((()=>[f(y(o.data.payPrice),1)])),_:1})):(g(),i(_,{key:1,class:"price"},{default:c((()=>[f(y((o.data.payPrice-o.data.exchangePrice).toFixed(2)),1)])),_:1}))],64)):(g(),p(h,{key:1},[A(_,{class:"price"},{default:c((()=>[f(y(o.data.exchangeIntegral),1)])),_:1}),A(_,{class:"price_fh",style:{padding:"8rpx 0 0 4rpx"}},{default:c((()=>[f("积分")])),_:1})],64))])),_:1}),A(_,{class:"order_btn",onClick:t.onSubmit},{default:c((()=>[f("提交订单")])),_:1},8,["onClick"])])),_:1})])),_:1})],64))])),_:1},8,["class"])}],["__scopeId","data-v-950c3f5e"]]);export{Y as default};
