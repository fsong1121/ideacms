var e,t,a,i;import{E as l,G as s,o,f as n,w as u,l as r,C as d,q as c,v as p,u as h,i as m,a3 as f,R as y,k as g,e as v,a4 as b,a5 as _,a6 as x,T as S,a7 as w,a8 as C,a9 as k,aa as $,ab as B,X as F,p as j,F as I,t as V,P,Q as z,x as T}from"./index-ozoT3Rek.js";import{_ as D,r as O}from"./uni-app.es.Cr4agT_b.js";import{_ as R}from"./uv-icon.uym7Qx1m.js";import{a as N}from"./uv-loading-page.tTreRwcr.js";import{_ as L}from"./uv-popup.Da3LgW3S.js";const A=D({name:"uv-textarea",mixins:[l,s,{props:{value:{type:[String,Number],default:""},modelValue:{type:[String,Number],default:""},placeholder:{type:[String,Number],default:""},placeholderClass:{type:String,default:"textarea-placeholder"},placeholderStyle:{type:[String,Object],default:"color: #c0c4cc"},height:{type:[String,Number],default:70},confirmType:{type:String,default:"done"},disabled:{type:Boolean,default:!1},count:{type:Boolean,default:!1},focus:{type:Boolean,default:!1},autoHeight:{type:Boolean,default:!1},fixed:{type:Boolean,default:!1},cursorSpacing:{type:Number,default:0},cursor:{type:[String,Number],default:""},showConfirmBar:{type:Boolean,default:!0},selectionStart:{type:Number,default:-1},selectionEnd:{type:Number,default:-1},adjustPosition:{type:Boolean,default:!0},disableDefaultPadding:{type:Boolean,default:!1},holdKeyboard:{type:Boolean,default:!1},maxlength:{type:[String,Number],default:140},border:{type:String,default:"surround"},formatter:{type:[Function,null],default:null},ignoreCompositionEvent:{type:Boolean,default:!0},confirmHold:{type:Boolean,default:!1},textStyle:{type:[Object,String],default:()=>{}},countStyle:{type:[Object,String],default:()=>{}},...null==(t=null==(e=uni.$uv)?void 0:e.props)?void 0:t.textarea}}],data:()=>({innerValue:"",focused:!1,innerFormatter:e=>e}),created(){this.innerValue=this.modelValue},watch:{value(e){this.innerValue=e},modelValue(e){this.innerValue=e}},computed:{textareaClass(){let e=[],{border:t,disabled:a}=this;return"surround"===t&&(e=e.concat(["uv-border","uv-textarea--radius"])),"bottom"===t&&(e=e.concat(["uv-border-bottom","uv-textarea--no-radius"])),a&&e.push("uv-textarea--disabled"),e.join(" ")},textareaStyle(){return this.$uv.deepMerge({},this.$uv.addStyle(this.customStyle))},maxlen(){return this.maxlength<0?this.maxlength<0?-1:140:this.maxlength},getCount(){try{return this.innerValue.length>this.maxlen?this.maxlen:this.innerValue.length}catch(e){return 0}}},methods:{setFormatter(e){this.innerFormatter=e},onFocus(e){this.$emit("focus",e)},onBlur(e){this.$emit("blur",e),this.$uv.formValidate(this,"blur")},onLinechange(e){this.$emit("linechange",e)},onInput(e){let{value:t=""}=e.detail||{};const a=(this.formatter||this.innerFormatter)(t);this.innerValue=t,this.$nextTick((()=>{this.innerValue=a,this.valueChange()}))},valueChange(){const e=this.innerValue;this.$nextTick((()=>{this.$emit("input",e),this.$emit("update:modelValue",e),this.$emit("change",e),this.$uv.formValidate(this,"change")}))},onConfirm(e){this.$emit("confirm",e)},onKeyboardheightchange(e){this.$emit("keyboardheightchange",e)}}},[["render",function(e,t,a,i,l,s){const v=f,b=y,_=g;return o(),n(_,{class:m(["uv-textarea",s.textareaClass]),style:d([s.textareaStyle])},{default:u((()=>[r(v,{class:"uv-textarea__field",value:l.innerValue,style:d([{height:e.autoHeight?"auto":e.$uv.addUnit(e.height)},e.$uv.addStyle(e.textStyle)]),placeholder:e.placeholder,"placeholder-style":e.$uv.addStyle(e.placeholderStyle,"string"),"placeholder-class":e.placeholderClass,disabled:e.disabled,focus:e.focus,autoHeight:e.autoHeight,fixed:e.fixed,cursorSpacing:e.cursorSpacing,cursor:e.cursor,showConfirmBar:e.showConfirmBar,selectionStart:e.selectionStart,selectionEnd:e.selectionEnd,adjustPosition:e.adjustPosition,disableDefaultPadding:e.disableDefaultPadding,holdKeyboard:e.holdKeyboard,maxlength:s.maxlen,confirmType:e.confirmType,ignoreCompositionEvent:e.ignoreCompositionEvent,"confirm-hold":e.confirmHold,onFocus:s.onFocus,onBlur:s.onBlur,onLinechange:s.onLinechange,onInput:s.onInput,onConfirm:s.onConfirm,onKeyboardheightchange:s.onKeyboardheightchange},null,8,["value","style","placeholder","placeholder-style","placeholder-class","disabled","focus","autoHeight","fixed","cursorSpacing","cursor","showConfirmBar","selectionStart","selectionEnd","adjustPosition","disableDefaultPadding","holdKeyboard","maxlength","confirmType","ignoreCompositionEvent","confirm-hold","onFocus","onBlur","onLinechange","onInput","onConfirm","onKeyboardheightchange"]),e.count&&-1!=s.maxlen?(o(),n(b,{key:0,class:"uv-textarea__count",style:d([{"background-color":e.disabled?"transparent":"#fff"},e.$uv.addStyle(e.countStyle)])},{default:u((()=>[c(p(s.getCount)+"/"+p(s.maxlen),1)])),_:1},8,["style"])):h("",!0)])),_:1},8,["class","style"])}],["__scopeId","data-v-2ec10f78"]]);const E=D({props:{src:{type:String,default:""},autoplay:{type:Boolean,default:!0}},data:()=>({videoSrc:"",show:!1}),computed:{getSec(){return this.src||this.videoSrc}},methods:{open(e){this.videoSrc=e,this.$refs.popup.open()},close(){this.$refs.popup.close()},change(e){this.show=e.show}}},[["render",function(e,t,a,i,l,s){const d=b,c=g,p=O(v("uv-popup"),L);return o(),n(p,{ref:"popup",onChange:s.change},{default:u((()=>[l.show?(o(),n(c,{key:0,class:"video-view"},{default:u((()=>[r(d,{class:"video",src:s.getSec,autoplay:a.autoplay},null,8,["src","autoplay"])])),_:1})):h("",!0)])),_:1},8,["onChange"])}],["__scopeId","data-v-ca563a8b"]]);function H(e,t){return["[object Object]","[object File]"].includes(Object.prototype.toString.call(e))?Object.keys(e).reduce(((a,i)=>(t.includes(i)||(a[i]=e[i]),a)),{}):{}}function K(e){return e.tempFiles.map((e=>({...H(e,["path"]),url:e.path,size:e.size,name:e.name,type:e.type})))}function U({accept:e,multiple:t,capture:a,compressed:i,maxDuration:l,sizeType:s,camera:o,maxCount:n}){return new Promise(((u,r)=>{switch(e){case"image":S({count:t?Math.min(n,9):1,sourceType:a,sizeType:s,success:e=>u(function(e){return e.tempFiles.map((e=>({...H(e,["path"]),type:"image",url:e.path,thumb:e.path,size:e.size,name:e.name})))}(e)),fail:r});break;case"video":x({sourceType:a,compressed:i,maxDuration:l,camera:o,success:e=>u(function(e){return[{...H(e,["tempFilePath","thumbTempFilePath","errMsg"]),type:"video",url:e.tempFilePath,thumb:e.thumbTempFilePath,size:e.size,name:e.name}]}(e)),fail:r});break;case"file":_({count:t?n:1,type:e,success:e=>u(K(e)),fail:r});break;default:_({count:t?n:1,type:"all",success:e=>u(K(e)),fail:r})}}))}const M=D({name:"uv-upload",emits:["error","beforeRead","oversize","afterRead","delete","clickPreview"],mixins:[l,s,{watch:{accept:{immediate:!0,handler(e){}}}},{props:{accept:{type:String,default:"image"},capture:{type:[String,Array],default:()=>["album","camera"]},compressed:{type:Boolean,default:!0},camera:{type:String,default:"back"},maxDuration:{type:Number,default:60},uploadIcon:{type:String,default:"camera-fill"},uploadIconColor:{type:String,default:"#D3D4D6"},useBeforeRead:{type:Boolean,default:!1},afterRead:{type:Function,default:null},beforeRead:{type:Function,default:null},previewFullImage:{type:Boolean,default:!0},previewFullVideo:{type:Boolean,default:!0},maxCount:{type:[String,Number],default:52},disabled:{type:Boolean,default:!1},imageMode:{type:String,default:"aspectFill"},name:{type:String,default:""},sizeType:{type:Array,default:()=>["original","compressed"]},multiple:{type:Boolean,default:!1},deletable:{type:Boolean,default:!0},maxSize:{type:[String,Number],default:Number.MAX_VALUE},fileList:{type:Array,default:()=>[]},uploadText:{type:String,default:""},width:{type:[String,Number],default:80},height:{type:[String,Number],default:80},previewImage:{type:Boolean,default:!0},...null==(i=null==(a=uni.$uv)?void 0:a.props)?void 0:i.upload}}],data:()=>({lists:[],isInCount:!0}),watch:{fileList:{deep:!0,immediate:!0,handler(){this.formatFileList()}},deletable(e){e||this.lists.map((e=>{e.deletable=this.deletable}))}},methods:{formatFileList(){const{fileList:e=[],maxCount:t}=this,a=e.map((e=>Object.assign(Object.assign({},e),{isImage:"image"===this.accept||w(e.url||e.thumb),isVideo:"video"===this.accept||C(e.url||e.thumb),deletable:"boolean"==typeof e.deletable?e.deletable:this.deletable})));this.lists=a,this.isInCount=a.length<t},chooseFile(){this.timer&&clearTimeout(this.timer),this.timer=setTimeout((()=>{const{maxCount:e,multiple:t,lists:a,disabled:i}=this;if(i)return;let l;try{l=k(this.capture)?this.capture:this.capture.split(",")}catch(s){l=[]}U(Object.assign({accept:this.accept,multiple:this.multiple,capture:l,compressed:this.compressed,maxDuration:this.maxDuration,sizeType:this.sizeType,camera:this.camera},{maxCount:e-a.length})).then((e=>{this.onBeforeRead(t?e:e[0])})).catch((e=>{this.$emit("error",e)}))}),100)},onBeforeRead(e){const{beforeRead:t,useBeforeRead:a}=this;let i=!0;$(t)&&(i=t(e,this.getDetail())),a&&(i=new Promise(((t,a)=>{this.$emit("beforeRead",Object.assign(Object.assign({file:e},this.getDetail()),{callback:e=>{e?t():a()}}))}))),i&&(B(i)?i.then((t=>this.onAfterRead(t||e))):this.onAfterRead(e))},getDetail(e){return{name:this.name,index:null==e?this.fileList.length:e}},onAfterRead(e){const{maxSize:t,afterRead:a}=this;(Array.isArray(e)?e.some((e=>e.size>t)):e.size>t)?this.$emit("oversize",Object.assign({file:e},this.getDetail())):("function"==typeof a&&a(e,this.getDetail()),this.$emit("afterRead",Object.assign({file:e},this.getDetail())))},deleteItem(e){this.$emit("delete",Object.assign(Object.assign({},this.getDetail(e)),{file:this.fileList[e]}))},onPreviewImage(e,t){const a=this.$uv.deepClone(this.lists);a.map(((e,a)=>{a==t&&(e.current=!0)}));const i=a.filter((e=>e.isImage)).findIndex((e=>e.current));this.onClickPreview(e,t),e.isImage&&this.previewFullImage&&F({urls:this.lists.filter((e=>"image"===this.accept||w(e.url||e.thumb))).map((e=>e.url||e.thumb)),current:i,fail(){this.$uv.toast("预览图片失败")}})},onPreviewVideo(e,t){this.onClickPreview(e,t),this.previewFullVideo&&e.isVideo&&this.$refs.previewVideo.open(e.url)},onClickPreview(e,t){this.$emit("clickPreview",Object.assign(Object.assign({},e),this.getDetail(t)))}}},[["render",function(e,t,a,i,l,s){const f=T,b=O(v("uv-icon"),R),_=y,x=g,S=O(v("uv-loading-icon"),N),w=O(v("uv-preview-video"),E);return o(),n(x,{class:"uv-upload",style:d([e.$uv.addStyle(e.customStyle)])},{default:u((()=>[r(x,{class:"uv-upload__wrap"},{default:u((()=>[e.previewImage?(o(!0),j(I,{key:0},V(l.lists,((t,a)=>(o(),n(x,{class:"uv-upload__wrap__preview",key:a},{default:u((()=>[t.isImage||t.type&&"image"===t.type?(o(),n(f,{key:0,src:t.thumb||t.url,mode:e.imageMode,class:"uv-upload__wrap__preview__image",onClick:e=>s.onPreviewImage(t,a),style:d([{width:e.$uv.addUnit(e.width),height:e.$uv.addUnit(e.height)}])},null,8,["src","mode","onClick","style"])):(o(),n(x,{key:1,class:"uv-upload__wrap__preview__other",onClick:e=>s.onPreviewVideo(t,a),style:d([{width:e.$uv.addUnit(e.width),height:e.$uv.addUnit(e.height)}])},{default:u((()=>[r(b,{color:"#80CBF9",size:"26",name:t.isVideo||t.type&&"video"===t.type?"movie":"folder"},null,8,["name"]),r(_,{class:"uv-upload__wrap__preview__other__text"},{default:u((()=>[c(p(t.isVideo||t.type&&"video"===t.type?"视频":"文件"),1)])),_:2},1024)])),_:2},1032,["onClick","style"])),"uploading"===t.status||"failed"===t.status?(o(),n(x,{key:2,class:"uv-upload__status"},{default:u((()=>[r(x,{class:"uv-upload__status__icon"},{default:u((()=>["failed"===t.status?(o(),n(b,{key:0,name:"close-circle",color:"#ffffff",size:"25"})):(o(),n(S,{key:1,size:"22",mode:"circle"}))])),_:2},1024),t.message?(o(),n(_,{key:0,class:"uv-upload__status__message"},{default:u((()=>[c(p(t.message),1)])),_:2},1024)):h("",!0)])),_:2},1024)):h("",!0),"uploading"!==t.status&&(e.deletable||t.deletable)?(o(),n(x,{key:3,class:"uv-upload__deletable",onClick:P((e=>s.deleteItem(a)),["stop"])},{default:u((()=>[r(x,{class:"uv-upload__deletable__icon"},{default:u((()=>[r(b,{name:"close",color:"#ffffff",size:"10"})])),_:1})])),_:2},1032,["onClick"])):h("",!0),"success"===t.status?(o(),n(x,{key:4,class:"uv-upload__success"},{default:u((()=>[r(x,{class:"uv-upload__success__icon"},{default:u((()=>[r(b,{name:"checkmark",color:"#ffffff",size:"12"})])),_:1})])),_:1})):h("",!0)])),_:2},1024)))),128)):h("",!0),l.isInCount?(o(),n(x,{key:1,onClick:s.chooseFile},{default:u((()=>[z(e.$slots,"default",{},(()=>[r(x,{class:m(["uv-upload__button",[e.disabled&&"uv-upload__button--disabled"]]),"hover-class":e.disabled?"":"uv-upload__button--hover","hover-stay-time":"150",onClick:P(s.chooseFile,["stop"]),style:d([{width:e.$uv.addUnit(e.width),height:e.$uv.addUnit(e.height)}])},{default:u((()=>[r(b,{name:e.uploadIcon,size:"26",color:e.uploadIconColor},null,8,["name","color"]),e.uploadText?(o(),n(_,{key:0,class:"uv-upload__button__text"},{default:u((()=>[c(p(e.uploadText),1)])),_:1})):h("",!0)])),_:1},8,["hover-class","onClick","class","style"])]),!0)])),_:3},8,["onClick"])):h("",!0)])),_:3}),r(w,{ref:"previewVideo"},null,512)])),_:3},8,["style"])}],["__scopeId","data-v-bf1dd02f"]]);export{A as _,M as a};