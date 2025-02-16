var t,e;import{K as i,o as n,p as s,f as h,C as a,u as o,L as r,M as d,N as c,E as l,O as u,P as m,k as g}from"./index-qqngRkUa.js";import{_ as p}from"./uni-app.es.DMzxE133.js";const f=p({name:"jp-verification-literalness",props:{securityCode:{type:String,default:""},codeLength:{type:Number,default:4},contentWidth:{type:Number,default:120},contentHeight:{type:Number,default:60},lineLength:{type:Number,default:8},backgroundColor:{type:String,default:"rgb(238,226,224)"},lineColorList:{type:Array,default:()=>["rgba(238,0,0,.5)","rgba(0, 170, 255,.5)","rgba(0, 170, 0,.5)","rgba(0, 0, 0,.5)","rgba(153, 146, 255,.5)"]},colorList:{type:Array,default:()=>["rgb(238,0,0)","rgb(0, 170, 255)","rgb(0, 170, 0)","rgb(0, 0, 0)","rgb(153, 146, 255)"]}},computed:{canvasId(){return`lime-signature${this._.uid}`}},data:()=>({identifyCode:""}),watch:{securityCode(){this.drawPic()}},methods:{verification(t){return this.identifyCode.toLowerCase()==t.toLowerCase()},randomNum:(t,e)=>Math.floor(Math.random()*(e-t)+t),getcheckCode(){let t="";const e=this.codeLength,i=[1,2,3,4,5,6,7,8,9,"A","B","C","D","E","F","G","H","I","J","K","L","M","N","P","Q","R","S","T","U","V","W","X","Y","Z"];for(let n=0;n<e;n++){t+=i[Math.floor(34*Math.random())]}this.identifyCode=t},drawTap(){this.securityCode?this.$emit("getCode"):this.drawPic()},drawPic(){this.securityCode?this.identifyCode=this.securityCode:this.getcheckCode();let t=i(this.canvasId,this);t.setTextBaseline("bottom"),t.setFillStyle(this.backgroundColor),t.fillRect(0,0,this.contentWidth,this.contentHeight);for(let e=0;e<this.identifyCode.length;e++)this.drawText(t,this.identifyCode[e],e);this.drawLine(t),t.draw()},drawText(t,e,i){let n=Math.floor(Math.random()*this.colorList.length);t.setFillStyle(this.colorList[n]);let s=Math.trunc(this.contentWidth/this.identifyCode.length);t.setFontSize(this.randomNum(s,s));let h=i*(this.contentWidth/(this.identifyCode.length+1))+Math.trunc(s/2),a=s+5;var o=this.randomNum(-10,10);t.translate(h,a),t.rotate(o*Math.PI/180),t.fillText(e,0,0),t.rotate(-o*Math.PI/180),t.translate(-h,-a)},drawLine(t){for(let e=0;e<this.lineLength;e++){let e=Math.floor(Math.random()*this.lineColorList.length);t.setStrokeStyle(this.lineColorList[e]),t.beginPath();let i=this.randomNum(0,this.contentWidth),n=this.randomNum(0,this.contentHeight),s=this.randomNum(0,this.contentWidth),h=this.randomNum(0,this.contentHeight);t.moveTo(i,n),t.lineTo(s,h),t.stroke()}}},mounted(){this.drawPic()}},[["render",function(t,e,i,d,c,l){const u=r;return n(),s("div",{style:a({height:i.contentHeight+"px",width:i.contentWidth+"px"})},[l.canvasId?(n(),h(u,{key:0,onClick:l.drawTap,id:l.canvasId,canvasId:l.canvasId,width:i.contentWidth,height:i.contentHeight,style:a({height:i.contentHeight+"px",width:i.contentWidth+"px"})},null,8,["onClick","id","canvasId","width","height","style"])):o("",!0)],4)}]]);const y=p({name:"uv-code",mixins:[d,c,{props:{seconds:{type:[String,Number],default:60},startText:{type:String,default:"获取验证码"},changeText:{type:String,default:"X秒重新获取"},endText:{type:String,default:"重新获取"},keepRunning:{type:Boolean,default:!1},uniqueKey:{type:String,default:""},...null==(e=null==(t=uni.$uv)?void 0:t.props)?void 0:e.code}}],data(){return{secNum:this.seconds,timer:null,canGetCode:!0}},mounted(){this.checkKeepRunning()},watch:{seconds:{immediate:!0,handler(t){this.secNum=t}}},methods:{checkKeepRunning(){let t=Number(l(this.uniqueKey+"_$uCountDownTimestamp"));if(!t)return this.changeEvent(this.startText);let e=Math.floor(+new Date/1e3);this.keepRunning&&t&&t>e?(this.secNum=t-e,u(this.uniqueKey+"_$uCountDownTimestamp"),this.start()):this.changeEvent(this.startText)},start(){this.timer&&(clearInterval(this.timer),this.timer=null),this.$emit("start"),this.canGetCode=!1,this.changeEvent(this.changeText.replace(/x|X/,this.secNum)),this.timer=setInterval((()=>{--this.secNum?this.changeEvent(this.changeText.replace(/x|X/,this.secNum)):(clearInterval(this.timer),this.timer=null,this.changeEvent(this.endText),this.secNum=this.seconds,this.$emit("end"),this.canGetCode=!0)}),1e3),this.setTimeToStorage()},reset(){this.canGetCode=!0,clearInterval(this.timer),this.secNum=this.seconds,this.changeEvent(this.endText)},changeEvent(t){this.$emit("change",t)},setTimeToStorage(){if(this.keepRunning&&this.timer&&this.secNum>0&&this.secNum<=this.seconds){let t=Math.floor(+new Date/1e3);m({key:this.uniqueKey+"_$uCountDownTimestamp",data:t+Number(this.secNum)})}}},unmounted(){this.setTimeToStorage(),clearTimeout(this.timer),this.timer=null}},[["render",function(t,e,i,s,a,o){const r=g;return n(),h(r,{class:"uv-code"})}]]);export{f as _,y as a};
