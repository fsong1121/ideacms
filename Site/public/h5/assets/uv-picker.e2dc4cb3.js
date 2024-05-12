var e,t,n,l;import{E as o,G as i,o as a,e as s,w as r,k as c,C as u,p as d,u as m,t as h,f as p,P as f,R as v,j as C,v as y,l as g,F as x,q as _,a1 as I,a2 as k}from"./index-8b182b74.js";import{_ as $,r as b}from"./uni-app.es.90f08a56.js";import{a as S}from"./uv-loading-page.792746ec.js";import{_ as w}from"./uv-popup.eb2a5516.js";const T=$({name:"uv-toolbar",emits:["confirm","cancel"],mixins:[o,i,{props:{show:{type:Boolean,default:!0},showBorder:{type:Boolean,default:!1},cancelText:{type:String,default:"取消"},confirmText:{type:String,default:"确认"},cancelColor:{type:String,default:"#909193"},confirmColor:{type:String,default:"#3c9cff"},title:{type:String,default:""},...null==(t=null==(e=uni.$uv)?void 0:e.props)?void 0:t.toolbar}}],methods:{cancel(){this.$emit("cancel")},confirm(){this.$emit("confirm")}}},[["render",function(e,t,n,l,o,i){const y=v,g=C;return e.show?(a(),s(g,{key:0,class:p(["uv-toolbar",{"uv-border-bottom":e.showBorder}]),onTouchmove:f(e.noop,["stop","prevent"])},{default:r((()=>[c(g,{class:"uv-toolbar__cancel__wrapper","hover-class":"uv-hover-class"},{default:r((()=>[c(y,{class:"uv-toolbar__wrapper__cancel",onClick:i.cancel,style:u({color:e.cancelColor})},{default:r((()=>[d(m(e.cancelText),1)])),_:1},8,["onClick","style"])])),_:1}),e.title?(a(),s(y,{key:0,class:"uv-toolbar__title uv-line-1"},{default:r((()=>[d(m(e.title),1)])),_:1})):h("",!0),c(g,{class:"uv-toolbar__confirm__wrapper","hover-class":"uv-hover-class"},{default:r((()=>[c(y,{class:"uv-toolbar__wrapper__confirm",onClick:i.confirm,style:u({color:e.confirmColor})},{default:r((()=>[d(m(e.confirmText),1)])),_:1},8,["onClick","style"])])),_:1})])),_:1},8,["class","onTouchmove"])):h("",!0)}],["__scopeId","data-v-2f73cab8"]]);const B=$({name:"uv-picker",emits:["confirm","cancel","close","change"],mixins:[o,i,{props:{showToolbar:{type:Boolean,default:!0},title:{type:String,default:""},round:{type:[String,Number],default:0},columns:{type:Array,default:()=>[]},loading:{type:Boolean,default:!1},itemHeight:{type:[String,Number],default:44},cancelText:{type:String,default:"取消"},confirmText:{type:String,default:"确定"},cancelColor:{type:String,default:"#909193"},confirmColor:{type:String,default:"#3c9cff"},color:{type:String,default:""},activeColor:{type:String,default:""},visibleItemCount:{type:[String,Number],default:5},keyName:{type:String,default:"text"},closeOnClickOverlay:{type:Boolean,default:!0},closeOnClickConfirm:{type:Boolean,default:!0},defaultIndex:{type:Array,default:()=>[]},immediateChange:{type:Boolean,default:!0},...null==(l=null==(n=uni.$uv)?void 0:n.props)?void 0:l.picker}}],computed:{textStyle(){return(e,t)=>{const n={display:"block"};return this.color&&(n.color=this.color),this.activeColor&&t===this.innerIndex[e]&&(n.color=this.activeColor),n}}},data:()=>({lastIndex:[],innerIndex:[],innerColumns:[],columnIndex:0}),watch:{defaultIndex:{immediate:!0,handler(e){this.setIndexs(e,!0)}},columns:{deep:!0,immediate:!0,handler(e){this.setColumns(e)}}},methods:{open(){this.$refs.pickerPopup.open()},close(){this.$refs.pickerPopup.close()},popupChange(e){e.show||this.$emit("close")},getItemText(e){return this.$uv.test.object(e)?e[this.keyName]:e},cancel(){this.$emit("cancel"),this.close()},confirm(){this.$emit("confirm",this.$uv.deepClone({indexs:this.innerIndex,value:this.innerColumns.map(((e,t)=>e[this.innerIndex[t]])),values:this.innerColumns})),this.closeOnClickConfirm&&this.close()},changeHandler(e){const{value:t}=e.detail;let n=0,l=0;for(let i=0;i<t.length;i++){let e=t[i];if(e!==(this.lastIndex[i]||0)){l=i,n=e;break}}this.columnIndex=l;const o=this.innerColumns;this.setLastIndex(t),this.setIndexs(t),this.$emit("change",{value:this.innerColumns.map(((e,n)=>e[t[n]])),index:n,indexs:t,values:o,columnIndex:l})},setIndexs(e,t){this.innerIndex=this.$uv.deepClone(e),t&&this.setLastIndex(e)},setLastIndex(e){this.lastIndex=this.$uv.deepClone(e)},setColumnValues(e,t){this.innerColumns.splice(e,1,t);let n=this.$uv.deepClone(this.innerIndex);for(let l=0;l<this.innerColumns.length;l++)l>this.columnIndex&&(n[l]=0);this.setIndexs(n)},getColumnValues(e){return(async()=>{await this.$uv.sleep()})(),this.innerColumns[e]},setColumns(e){this.innerColumns=this.$uv.deepClone(e),0===this.innerIndex.length&&(this.innerIndex=new Array(e.length).fill(0))},getIndexs(){return this.innerIndex},getValues(){return(async()=>{await this.$uv.sleep()})(),this.innerColumns.map(((e,t)=>e[this.innerIndex[t]]))}}},[["render",function(e,t,n,l,o,i){const p=b(y("uv-toolbar"),T),f=v,$=I,B=k,H=b(y("uv-loading-icon"),S),O=C,j=b(y("uv-popup"),w);return a(),s(j,{ref:"pickerPopup",mode:"bottom",round:e.round,"close-on-click-overlay":e.closeOnClickOverlay,onChange:i.popupChange},{default:r((()=>[c(O,{class:"uv-picker"},{default:r((()=>[e.showToolbar?(a(),s(p,{key:0,cancelColor:e.cancelColor,confirmColor:e.confirmColor,cancelText:e.cancelText,confirmText:e.confirmText,title:e.title,onCancel:i.cancel,onConfirm:i.confirm},null,8,["cancelColor","confirmColor","cancelText","confirmText","title","onCancel","onConfirm"])):h("",!0),c(B,{class:"uv-picker__view",indicatorStyle:`height: ${e.$uv.addUnit(e.itemHeight)}`,value:o.innerIndex,immediateChange:e.immediateChange,style:u({height:`${e.$uv.addUnit(e.visibleItemCount*e.itemHeight)}`}),onChange:i.changeHandler},{default:r((()=>[(a(!0),g(x,null,_(o.innerColumns,((t,n)=>(a(),s($,{key:n,class:"uv-picker__view__column"},{default:r((()=>[e.$uv.test.array(t)?(a(!0),g(x,{key:0},_(t,((t,l)=>(a(),s(f,{class:"uv-picker__view__column__item uv-line-1",key:l,style:u([{height:e.$uv.addUnit(e.itemHeight),lineHeight:e.$uv.addUnit(e.itemHeight),fontWeight:l===o.innerIndex[n]?"bold":"normal"},i.textStyle(n,l)])},{default:r((()=>[d(m(i.getItemText(t)),1)])),_:2},1032,["style"])))),128)):h("",!0)])),_:2},1024)))),128))])),_:1},8,["indicatorStyle","value","immediateChange","style","onChange"]),e.loading?(a(),s(O,{key:1,class:"uv-picker--loading"},{default:r((()=>[c(H,{mode:"circle"})])),_:1})):h("",!0)])),_:1})])),_:1},8,["round","close-on-click-overlay","onChange"])}],["__scopeId","data-v-a45445e8"]]);export{B as _};
