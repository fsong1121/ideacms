import{g as e,h as s,n as a,d as t,r as l,a as o,o as r,e as d,w as i,f as c,i as _,j as n,k as p,l as u,p as y,F as f,q as g,t as w,v as h,W as m,u as T,x as k}from"./index-8b182b74.js";import{_ as v}from"./uv-loading-page.792746ec.js";import{_ as x,r as L}from"./uni-app.es.90f08a56.js";import{_ as C}from"./uv-divider.4e733a64.js";import{_ as j}from"./uv-empty.ae111bf2.js";import"./uv-icon.70699d37.js";const D=x({data:()=>({theme:"",loading:!0,keys:"",order:"id",orderType:"desc",activityType:"",size:20,page:1,loadingComplete:!1,dataList:[]}),onLoad(e){let s=this;Reflect.ownKeys(e).length>0&&e.hasOwnProperty("key")&&(s.keys=e.key),s.initData()},onShow(){let s=_(),a=e(),t={goods_id:0,url:a[a.length-1].route,platform:s.globalData.systemInfo.uniPlatform,model:s.globalData.systemInfo.deviceModel};this.$apis.visit.add(t)},methods:{async initData(e=!1){let a,t=_(),l=this;await l.$onLaunched,l.theme=t.globalData.theme,a=await l.$apis.goods.getList({field:"id,title,pic,price,market_price",keys:l.keys,activity_type:l.activityType,order:l.order,order_type:l.orderType,size:l.size,page:l.page}),l.dataList=1==e?l.dataList.concat(a.data):a.data,a.count<l.size&&(l.loadingComplete=!0),s(),l.loading=!1},turnUrl(e="",s=1){e&&"#"!=e&&(1==s&&a({url:e}),2==s&&t({url:e}),3==s&&l({url:e}))},changeType(e){let s=this;s.order=e;let a="desc"==s.orderType?"asc":"desc";"id"==e&&(a="desc"),s.orderType=a,s.page=1,s.loadingComplete=!1,o({title:"加载中"}),s.initData()},scrollToLower(){let e=this;e.loadingComplete||(e.page=e.page+1,o({title:"加载中"}),e.initData(!0))}}},[["render",function(e,s,a,t,l,o){const _=L(h("uv-loading-page"),v),x=n,D=k,z=L(h("uv-divider"),C),b=L(h("uv-empty"),j),I=m;return r(),d(x,{class:c(["content",l.theme])},{default:i((()=>[p(_,{loading:l.loading},null,8,["loading"]),l.loading?w("",!0):(r(),u(f,{key:0},[p(x,{class:"header"},{default:i((()=>[p(x,{class:"header_ctt"},{default:i((()=>[p(x,{class:"list_top"},{default:i((()=>[p(x,{class:"list_top_ctr"},{default:i((()=>[p(x,{class:"list_top_cell",onClick:s[0]||(s[0]=e=>o.changeType("id"))},{default:i((()=>[p(x,{class:c({list_top_text_on:"id"==l.order})},{default:i((()=>[y("综合")])),_:1},8,["class"])])),_:1})])),_:1}),p(x,{class:"list_top_ctr"},{default:i((()=>[p(x,{class:"list_top_cell",onClick:s[1]||(s[1]=e=>o.changeType("sales"))},{default:i((()=>[p(x,{class:c({list_top_text_on:"sales"==l.order})},{default:i((()=>[y("销量")])),_:1},8,["class"]),p(x,{class:"list_top_arrow"},{default:i((()=>[p(x,{class:c({arrow_up_on:"sales"==l.order&&"asc"==l.orderType,arrow_up:"sales"!==l.order||"asc"!==l.orderType})},null,8,["class"]),p(x,{class:c({arrow_down_on:"sales"==l.order&&"desc"==l.orderType,arrow_down:"sales"!==l.order||"desc"!==l.orderType})},null,8,["class"])])),_:1})])),_:1})])),_:1}),p(x,{class:"list_top_ctr"},{default:i((()=>[p(x,{class:"list_top_cell",onClick:s[2]||(s[2]=e=>o.changeType("price"))},{default:i((()=>[p(x,{class:c({list_top_text_on:"price"===l.order})},{default:i((()=>[y("价格")])),_:1},8,["class"]),p(x,{class:"list_top_arrow"},{default:i((()=>[p(x,{class:c({arrow_up_on:"price"==l.order&&"asc"==l.orderType,arrow_up:"price"!==l.order||"asc"!==l.orderType})},null,8,["class"]),p(x,{class:c({arrow_down_on:"price"==l.order&&"desc"==l.orderType,arrow_down:"price"!==l.order||"desc"!==l.orderType})},null,8,["class"])])),_:1})])),_:1})])),_:1}),p(x,{class:"list_top_ctr"},{default:i((()=>[p(x,{class:"list_top_cell",onClick:s[3]||(s[3]=e=>o.changeType("hits"))},{default:i((()=>[p(x,{class:c({list_top_text_on:"hits"===l.order})},{default:i((()=>[y("热度")])),_:1},8,["class"]),p(x,{class:"list_top_arrow"},{default:i((()=>[p(x,{class:c({arrow_up_on:"hits"==l.order&&"asc"==l.orderType,arrow_up:"hits"!==l.order||"asc"!==l.orderType})},null,8,["class"]),p(x,{class:c({arrow_down_on:"hits"==l.order&&"desc"==l.orderType,arrow_down:"hits"!==l.order||"desc"!==l.orderType})},null,8,["class"])])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1}),p(x,{class:"wrap"},{default:i((()=>[p(I,{class:"list_ctr","scroll-y":"true",onScrolltolower:o.scrollToLower},{default:i((()=>[p(x,{class:"goods_list_ctr"},{default:i((()=>[(r(!0),u(f,null,g(l.dataList,((e,s)=>(r(),d(x,{class:"goods_list_cell",key:s},{default:i((()=>[p(x,{onClick:s=>o.turnUrl("/pages/goods/detail?id="+e.id)},{default:i((()=>[p(D,{mode:"widthFix",src:e.pic},null,8,["src"]),p(x,{class:"goods_list_t1"},{default:i((()=>[y(T(e.title),1)])),_:2},1024),p(x,{class:"goods_list_t2"},{default:i((()=>[p(x,null,{default:i((()=>[y("¥"+T(e.price),1)])),_:2},1024),p(x,{class:"span"},{default:i((()=>[y("¥"+T(e.market_price),1)])),_:2},1024)])),_:2},1024)])),_:2},1032,["onClick"])])),_:2},1024)))),128)),l.dataList.length>0?(r(),d(x,{key:0,class:"line_ctr"},{default:i((()=>[p(z,{textSize:"24rpx",text:"这是我的底线"})])),_:1})):w("",!0),l.dataList.length<1?(r(),d(x,{key:1,class:"none_ctr"},{default:i((()=>[p(b,{icon:"/static/images/none.png",text:"暂无商品"})])),_:1})):w("",!0)])),_:1})])),_:1},8,["onScrolltolower"])])),_:1})],64))])),_:1},8,["class"])}],["__scopeId","data-v-bc369e90"]]);export{D as default};
