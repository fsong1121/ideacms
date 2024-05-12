var e,t,a,i;import{_ as l}from"./uv-icon.70699d37.js";import{E as s,G as o,v as r,o as n,e as c,w as h,k as d,f as u,P as p,C as b,Q as C,p as m,u as v,j as f,R as k}from"./index-8b182b74.js";import{_ as S,r as D}from"./uni-app.es.90f08a56.js";const y=S({name:"uv-checkbox",mixins:[s,o,{props:{name:{type:[String,Number,Boolean],default:""},shape:{type:String,default:""},size:{type:[String,Number],default:""},checked:{type:Boolean,default:!1},disabled:{type:[String,Boolean],default:""},activeColor:{type:String,default:""},inactiveColor:{type:String,default:""},iconSize:{type:[String,Number],default:""},iconColor:{type:String,default:""},label:{type:[String,Number,Boolean],default:""},labelSize:{type:[String,Number],default:""},labelColor:{type:String,default:""},labelDisabled:{type:[String,Boolean],default:""},...null==(t=null==(e=uni.$uv)?void 0:e.props)?void 0:t.checkbox}}],data:()=>({isChecked:!1,parentData:{iconSize:12,labelDisabled:null,disabled:null,shape:"square",activeColor:null,inactiveColor:null,size:18,value:null,modelValue:null,iconColor:null,placement:"row",borderBottom:!1,iconPlacement:"left",labelSize:14,labelColor:"#303133"}}),computed:{elDisabled(){return""!==this.disabled?this.disabled:null!==this.parentData.disabled&&this.parentData.disabled},elLabelDisabled(){return""!==this.labelDisabled?this.labelDisabled:null!==this.parentData.labelDisabled&&this.parentData.labelDisabled},elSize(){return this.size?this.size:this.parentData.size?this.parentData.size:21},elIconSize(){return this.iconSize?this.iconSize:this.parentData.iconSize?this.parentData.iconSize:12},elActiveColor(){return this.activeColor?this.activeColor:this.parentData.activeColor?this.parentData.activeColor:"#2979ff"},elInactiveColor(){return this.inactiveColor?this.inactiveColor:this.parentData.inactiveColor?this.parentData.inactiveColor:"#c8c9cc"},elLabelColor(){return this.labelColor?this.labelColor:this.parentData.labelColor?this.parentData.labelColor:"#606266"},elShape(){return this.shape?this.shape:this.parentData.shape?this.parentData.shape:"circle"},elLabelSize(){return this.$uv.addUnit(this.labelSize?this.labelSize:this.parentData.labelSize?this.parentData.labelSize:"15")},elIconColor(){const e=this.iconColor?this.iconColor:this.parentData.iconColor?this.parentData.iconColor:"#ffffff";return this.elDisabled?this.isChecked?this.elInactiveColor:"transparent":this.isChecked?e:"transparent"},iconClasses(){let e=[];return e.push("uv-checkbox__icon-wrap--"+this.elShape),this.elDisabled&&e.push("uv-checkbox__icon-wrap--disabled"),this.isChecked&&this.elDisabled&&e.push("uv-checkbox__icon-wrap--disabled--checked"),e},iconWrapStyle(){const e={};return e.backgroundColor=this.isChecked&&!this.elDisabled?this.elActiveColor:"#ffffff",e.borderColor=this.isChecked&&!this.elDisabled?this.elActiveColor:this.elInactiveColor,e.width=this.$uv.addUnit(this.elSize),e.height=this.$uv.addUnit(this.elSize),"right"===this.parentData.iconPlacement&&(e.marginRight=0),e},checkboxStyle(){const e={};return this.parentData.borderBottom&&"row"===this.parentData.placement&&this.$uv.error("检测到您将borderBottom设置为true，需要同时将uv-checkbox-group的placement设置为column才有效"),this.parentData.borderBottom&&"column"===this.parentData.placement&&(e.paddingBottom="8px"),this.$uv.deepMerge(e,this.$uv.addStyle(this.customStyle))}},created(){this.init()},methods:{init(){this.updateParentData(),this.parent||this.$uv.error("uv-checkbox必须搭配uv-checkbox-group组件使用"),this.$nextTick((()=>{let e=[];this.parentData.value.length?e=this.parentData.value:this.parentData.modelValue.length&&(e=this.parentData.modelValue),this.checked?this.isChecked=!0:this.$uv.test.array(e)&&(this.isChecked=e.some((e=>e===this.name)))}))},updateParentData(){this.getParentData("uv-checkbox-group")},wrapperClickHandler(e){"right"===this.parentData.iconPlacement&&this.iconClickHandler(e)},iconClickHandler(e){this.preventEvent(e),this.elDisabled||this.setRadioCheckedStatus()},labelClickHandler(e){this.preventEvent(e),this.elLabelDisabled||this.elDisabled||this.setRadioCheckedStatus()},emitEvent(){this.$emit("change",this.isChecked),this.$nextTick((()=>{this.$uv.formValidate(this,"change")}))},setRadioCheckedStatus(){this.isChecked=!this.isChecked,this.emitEvent(),"function"==typeof this.parent.unCheckedOther&&this.parent.unCheckedOther(this)}},watch:{checked(){this.isChecked=this.checked}}},[["render",function(e,t,a,i,s,o){const S=D(r("uv-icon"),l),y=f,g=k;return n(),c(y,{class:u(["uv-checkbox",[`uv-checkbox-label--${s.parentData.iconPlacement}`,s.parentData.borderBottom&&"column"===s.parentData.placement&&"uv-border-bottom"]]),style:b([o.checkboxStyle]),onClick:p(o.wrapperClickHandler,["stop"])},{default:h((()=>[d(y,{class:u(["uv-checkbox__icon-wrap",o.iconClasses]),onClick:p(o.iconClickHandler,["stop"]),style:b([o.iconWrapStyle])},{default:h((()=>[C(e.$slots,"icon",{},(()=>[d(S,{class:"uv-checkbox__icon-wrap__icon",name:"checkbox-mark",size:o.elIconSize,color:o.elIconColor},null,8,["size","color"])]),!0)])),_:3},8,["onClick","class","style"]),d(y,{class:"uv-checkbox__label-wrap",onClick:p(o.labelClickHandler,["stop"])},{default:h((()=>[C(e.$slots,"default",{},(()=>[d(g,{style:b({color:o.elDisabled?o.elInactiveColor:o.elLabelColor,fontSize:o.elLabelSize,lineHeight:o.elLabelSize})},{default:h((()=>[m(v(e.label),1)])),_:1},8,["style"])]),!0)])),_:3},8,["onClick"])])),_:3},8,["style","onClick","class"])}],["__scopeId","data-v-351a1a67"]]);const g=S({name:"uv-checkbox-group",mixins:[s,o,{props:{value:{type:Array,default:()=>[]},modelValue:{type:Array,default:()=>[]},name:{type:String,default:""},shape:{type:String,default:"square"},disabled:{type:Boolean,default:!1},activeColor:{type:String,default:"#2979ff"},inactiveColor:{type:String,default:"#c8c9cc"},size:{type:[String,Number],default:18},placement:{type:String,default:"row"},labelSize:{type:[String,Number],default:14},labelColor:{type:[String],default:"#303133"},labelDisabled:{type:Boolean,default:!1},iconColor:{type:String,default:"#fff"},iconSize:{type:[String,Number],default:12},iconPlacement:{type:String,default:"left"},borderBottom:{type:Boolean,default:!1},...null==(i=null==(a=uni.$uv)?void 0:a.props)?void 0:i.checkboxGroup}}],computed:{parentData(){let e=[];return this.value.length?e=this.value:this.modelValue.length&&(e=this.modelValue),[e,this.disabled,this.inactiveColor,this.activeColor,this.size,this.labelDisabled,this.shape,this.iconSize,this.borderBottom,this.placement,this.labelSize,this.labelColor]},bemClass(){return this.bem("checkbox-group",["placement"])}},watch:{parentData(){this.children.length&&this.children.map((e=>{"function"==typeof e.init&&e.init()}))}},data:()=>({}),created(){this.children=[]},methods:{unCheckedOther(e){const t=[];this.children.map((e=>{e.isChecked&&t.push(e.name)})),this.$emit("update:modelValue",t),this.$emit("change",t)}}},[["render",function(e,t,a,i,l,s){const o=f;return n(),c(o,{class:u(["uv-checkbox-group",s.bemClass]),style:b([e.$uv.addStyle(this.customStyle)])},{default:h((()=>[C(e.$slots,"default",{},void 0,!0)])),_:3},8,["class","style"])}],["__scopeId","data-v-287a150a"]]);export{y as _,g as a};
