var e,t;import{E as a,G as l,v as i,o as s,e as o,w as n,k as d,P as c,l as u,F as r,q as h,f as m,C as f,t as _,j as p,g as v,A as g,a as y,h as I,r as x,i as b,p as k,u as V,x as $}from"./index-61bca09a.js";import{_ as L}from"./uv-loading-page.a3cea9e4.js";import{_ as C,r as w}from"./uni-app.es.c541313a.js";import{_ as R}from"./uv-icon.06d9cba4.js";import{_ as S,a as D}from"./uv-upload.ef8aebc2.js";import{_ as T}from"./uv-empty.b267d039.js";import"./uv-popup.0cc007cb.js";const U=C({name:"uv-rate",mixins:[a,l,{props:{value:{type:[String,Number],default:0},modelValue:{type:[String,Number],default:0},count:{type:[String,Number],default:5},disabled:{type:Boolean,default:!1},readonly:{type:Boolean,default:!1},size:{type:[String,Number],default:18},inactiveColor:{type:String,default:"#b2b2b2"},activeColor:{type:String,default:"#FA3534"},gutter:{type:[String,Number],default:4},minCount:{type:[String,Number],default:1},allowHalf:{type:Boolean,default:!1},activeIcon:{type:String,default:"star-fill"},inactiveIcon:{type:String,default:"star"},touchable:{type:Boolean,default:!1},...null==(t=null==(e=uni.$uv)?void 0:e.props)?void 0:t.rate}}],data:()=>({elId:"",elClass:"",rateBoxLeft:0,activeIndex:0,rateWidth:0,moving:!1}),watch:{value(e){this.activeIndex=e},modelValue(e){this.activeIndex=e}},created(){this.activeIndex=Number(this.value||this.modelValue),this.elId=this.$uv.guid(),this.elClass=this.$uv.guid()},mounted(){this.init()},methods:{init(){this.$uv.sleep(200).then((()=>{this.getRateItemRect(),this.getRateIconWrapRect()}))},async getRateItemRect(){await this.$uv.sleep(),this.$uvGetRect("#"+this.elId).then((e=>{this.rateBoxLeft=e.left}))},getRateIconWrapRect(){this.$uvGetRect("."+this.elClass).then((e=>{this.rateWidth=e.width}))},touchMove(e){if(!this.touchable)return;this.preventEvent(e);const t=e.changedTouches&&e.changedTouches[0].pageX||e.detail&&e.detail.pageX;this.getActiveIndex(t)},touchEnd(e){if(!this.touchable)return;this.preventEvent(e);const t=e.changedTouches&&e.changedTouches[0].pageX||e.detail&&e.detail.pageX;this.getActiveIndex(t)},clickHandler(e,t){if("ios"===this.$uv.os()&&this.moving)return;this.preventEvent(e);let a=0;a=e.changedTouches&&e.changedTouches[0].pageX||e.detail&&e.detail.pageX,this.getActiveIndex(a,!0)},changeEvent(){this.$emit("change",this.activeIndex),this.$emit("input",this.activeIndex),this.$emit("update:modelValue",this.activeIndex)},getActiveIndex(e,t=!1){if(this.disabled||this.readonly)return;const a=this.rateWidth*this.count+this.rateBoxLeft,l=e=this.$uv.range(this.rateBoxLeft,a,e)-this.rateBoxLeft;let i;if(this.allowHalf){i=Math.floor(l/this.rateWidth);const e=l%this.rateWidth;e<=this.rateWidth/2&&e>0?i+=.5:e>this.rateWidth/2&&i++}else{i=Math.floor(l/this.rateWidth);const e=l%this.rateWidth;t?e>0&&i++:e>this.rateWidth/2&&i++}this.activeIndex=Math.min(i,this.count),this.activeIndex<this.minCount&&(this.activeIndex=this.minCount),this.changeEvent(),setTimeout((()=>{this.moving=!0}),10),setTimeout((()=>{this.moving=!1}),10)}}},[["render",function(e,t,a,l,v,g){const y=w(i("uv-icon"),R),I=p;return s(),o(I,{class:"uv-rate",id:v.elId,ref:"uv-rate",style:f([e.$uv.addStyle(e.customStyle)])},{default:n((()=>[d(I,{class:"uv-rate__content",onTouchmove:c(g.touchMove,["stop"]),onTouchend:c(g.touchEnd,["stop"])},{default:n((()=>[(s(!0),u(r,null,h(Number(e.count),((t,a)=>(s(),o(I,{class:m(["uv-rate__content__item",[v.elClass]]),key:a},{default:n((()=>[d(I,{class:"uv-rate__content__item__icon-wrap",ref_for:!0,ref:"uv-rate__content__item__icon-wrap",onClick:c((e=>g.clickHandler(e,a+1)),["stop"])},{default:n((()=>[d(y,{name:Math.floor(v.activeIndex)>a?e.activeIcon:e.inactiveIcon,color:e.disabled?"#c8c9cc":Math.floor(v.activeIndex)>a?e.activeColor:e.inactiveColor,"custom-style":{"padding-left":e.$uv.addUnit(e.gutter/2),"padding-right":e.$uv.addUnit(e.gutter/2)},size:e.size},null,8,["name","color","custom-style","size"])])),_:2},1032,["onClick"]),e.allowHalf?(s(),o(I,{key:0,onClick:c((e=>g.clickHandler(e,a+1)),["stop"]),class:"uv-rate__content__item__icon-wrap uv-rate__content__item__icon-wrap--half",style:f([{width:e.$uv.addUnit(v.rateWidth/2)}]),ref_for:!0,ref:"uv-rate__content__item__icon-wrap"},{default:n((()=>[d(y,{name:Math.ceil(v.activeIndex)>a?e.activeIcon:e.inactiveIcon,color:e.disabled?"#c8c9cc":Math.ceil(v.activeIndex)>a?e.activeColor:e.inactiveColor,"custom-style":{"padding-left":e.$uv.addUnit(e.gutter/2),"padding-right":e.$uv.addUnit(e.gutter/2)},size:e.size},null,8,["name","color","custom-style","size"])])),_:2},1032,["onClick","style"])):_("",!0)])),_:2},1032,["class"])))),128))])),_:1},8,["onTouchmove","onTouchend"])])),_:1},8,["id","style"])}],["__scopeId","data-v-8a7d1d7f"]]);const W=C({data:()=>({theme:"",loading:!0,currentID:0,rate:[5,5,5],info:"",fileList:[],data:{}}),onLoad(e){let t=this;Reflect.ownKeys(e).length>0&&(t.currentID=e.hasOwnProperty("id")?e.id:0)},onShow(){let e=b(),t=v(),a={goods_id:0,url:t[t.length-1].route,platform:e.globalData.systemInfo.uniPlatform,model:e.globalData.systemInfo.deviceModel};this.$apis.visit.add(a),this.initData()},methods:{async initData(){let e,t=b(),a=this;if(await a.$onLaunched,a.theme=t.globalData.theme,e=await a.$apis.comment.getData({id:a.currentID}),0==e.code){a.data=e.data,a.info=e.data.info,a.rate[0]=e.data.goods_rate,a.rate[1]=e.data.express_rate,a.rate[2]=e.data.service_rate;let t=[];for(let a=0;a<e.data.picArr.length;a++){let l={url:e.data.picArr[a]};t[a]=l}a.fileList=t}else a.data={},a.info="",a.rate=[5,5,5],a.fileList=[];a.loading=!1},deletePic(e){this.fileList.splice(e.index,1)},async afterRead(e){let t=this,a=[].concat(e.file),l=t.fileList.length;a.map((e=>{t.fileList.push({...e,status:"uploading",message:"上传中"})}));for(let i=0;i<a.length;i++){const e=await this.$apis.upload.save({file:a[i].url,dir:"comment"});if(0==e.code){let a=t.fileList[l];t.fileList.splice(l,1,Object.assign(a,{status:"success",message:"",url:e.data.src,pic:e.data.file})),l++}}},onSubmit(){let e=this;uni.$uv.throttle((function(){let t=[];if(""!=e.info.replace(/\s/g,"")){for(let a=0;a<e.fileList.length;a++)t.push(e.fileList[a].pic);y({title:"提交中"}),e.$apis.comment.saveData({id:e.currentID,goods_rate:e.rate[0],express_rate:e.rate[1],service_rate:e.rate[2],info:e.info,pic:t.join(",")}).then((e=>{I(),0==e.code?x({url:"/pages/comment/list"}):g({title:e.msg,icon:"none",duration:2e3})}))}else g({title:"评价内容为空",icon:"error"})}))}}},[["render",function(e,t,a,l,c,h){const f=w(i("uv-loading-page"),L),v=p,g=$,y=w(i("uv-rate"),U),I=w(i("uv-textarea"),S),x=w(i("uv-upload"),D),b=w(i("uv-empty"),T);return s(),o(v,{class:m(["content",c.theme])},{default:n((()=>[d(f,{loading:c.loading},null,8,["loading"]),c.loading?_("",!0):(s(),u(r,{key:0},[d(v,{class:"wrap"},{default:n((()=>[d(v,{class:"m_ctr"},{default:n((()=>[d(v,{class:"space_ctr"}),d(v,{class:"yuan_ctr"},{default:n((()=>[d(v,{class:"goods_ctr"},{default:n((()=>[d(v,{class:"goods_pic"},{default:n((()=>[d(g,{mode:"widthFix",src:c.data.pic},null,8,["src"])])),_:1}),d(v,{class:"goods_ctt"},{default:n((()=>[d(v,{class:"goods_title"},{default:n((()=>[k(V(c.data.title),1)])),_:1}),d(v,{class:"goods_txt"},{default:n((()=>[k(V(c.data.spec_key_name),1)])),_:1})])),_:1})])),_:1}),d(v,{class:"comment_cell_ctr"},{default:n((()=>[d(v,{class:"txt"},{default:n((()=>[k("总体评价：")])),_:1}),d(v,{class:"rate_ctr"},{default:n((()=>[0==c.data.is_comment?(s(),o(y,{key:0,modelValue:c.rate[0],"onUpdate:modelValue":t[0]||(t[0]=e=>c.rate[0]=e)},null,8,["modelValue"])):(s(),o(y,{key:1,modelValue:c.rate[0],"onUpdate:modelValue":t[1]||(t[1]=e=>c.rate[0]=e),readonly:""},null,8,["modelValue"]))])),_:1})])),_:1}),d(v,{class:"comment_info"},{default:n((()=>[0==c.data.is_comment?(s(),o(I,{key:0,modelValue:c.info,"onUpdate:modelValue":t[2]||(t[2]=e=>c.info=e),placeholder:"把美好的事物分享给大家吧~"},null,8,["modelValue"])):(s(),o(I,{key:1,modelValue:c.info,"onUpdate:modelValue":t[3]||(t[3]=e=>c.info=e),disabled:"",placeholder:"把美好的事物分享给大家吧~"},null,8,["modelValue"]))])),_:1}),d(v,{class:"comment_pic"},{default:n((()=>[0==c.data.is_comment?(s(),o(x,{key:0,fileList:c.fileList,multiple:"",maxCount:8,onAfterRead:h.afterRead,onDelete:h.deletePic},null,8,["fileList","onAfterRead","onDelete"])):(s(),o(x,{key:1,fileList:c.fileList,maxCount:0,deletable:!1},null,8,["fileList"]))])),_:1})])),_:1}),d(v,{class:"yuan_ctr"},{default:n((()=>[d(v,{class:"comment_title"},{default:n((()=>[k("服务评价：")])),_:1}),d(v,{class:"comment_cell_ctr"},{default:n((()=>[d(v,{class:"txt"},{default:n((()=>[k("物流服务：")])),_:1}),d(v,{class:"rate_ctr"},{default:n((()=>[0==c.data.is_comment?(s(),o(y,{key:0,modelValue:c.rate[1],"onUpdate:modelValue":t[4]||(t[4]=e=>c.rate[1]=e)},null,8,["modelValue"])):(s(),o(y,{key:1,modelValue:c.rate[1],"onUpdate:modelValue":t[5]||(t[5]=e=>c.rate[1]=e),readonly:""},null,8,["modelValue"]))])),_:1})])),_:1}),d(v,{class:"comment_cell_ctr"},{default:n((()=>[d(v,{class:"txt"},{default:n((()=>[k("服务态度：")])),_:1}),d(v,{class:"rate_ctr"},{default:n((()=>[0==c.data.is_comment?(s(),o(y,{key:0,modelValue:c.rate[2],"onUpdate:modelValue":t[6]||(t[6]=e=>c.rate[2]=e)},null,8,["modelValue"])):(s(),o(y,{key:1,modelValue:c.rate[2],"onUpdate:modelValue":t[7]||(t[7]=e=>c.rate[2]=e),readonly:""},null,8,["modelValue"]))])),_:1})])),_:1})])),_:1})])),_:1}),0===Object.keys(c.data).length?(s(),o(v,{key:0,class:"none_ctr"},{default:n((()=>[d(b,{icon:"/static/images/none.png",text:"暂无待评价商品"})])),_:1})):_("",!0),d(v,{class:"space_footer"})])),_:1}),Object.keys(c.data).length>0&&0==c.data.is_comment?(s(),o(v,{key:0,class:"footer"},{default:n((()=>[d(v,{class:"footer_ctt"},{default:n((()=>[d(v,{class:"comment_btn",onClick:h.onSubmit},{default:n((()=>[k("确认提交")])),_:1},8,["onClick"])])),_:1})])),_:1})):_("",!0)],64))])),_:1},8,["class"])}],["__scopeId","data-v-b5d86b26"]]);export{W as default};