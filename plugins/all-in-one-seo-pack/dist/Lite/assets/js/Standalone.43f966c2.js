var c=Object.defineProperty;var i=Object.getOwnPropertySymbols;var p=Object.prototype.hasOwnProperty,h=Object.prototype.propertyIsEnumerable;var r=(o,s,e)=>s in o?c(o,s,{enumerable:!0,configurable:!0,writable:!0,value:e}):o[s]=e,t=(o,s)=>{for(var e in s||(s={}))p.call(s,e)&&r(o,e,s[e]);if(i)for(var e of i(s))h.call(s,e)&&r(o,e,s[e]);return o};import{d as l,m as n,g as d}from"./index.01898232.js";const y={computed:t({},l(["currentPost","options","dynamicOptions"])),methods:{updateAioseo(){this.$set(this.$store.state,"currentPost",n(t({},this.$store.state.currentPost),t({},window.aioseo.currentPost)))}},mounted(){this.$nextTick(()=>{window.addEventListener("updateAioseo",this.updateAioseo)})},beforeDestroy(){window.removeEventListener("updateAioseo",this.updateAioseo)},async created(){const{options:o,dynamicOptions:s,currentPost:e,internalOptions:a,tags:u}=await d();this.$set(this.$store.state,"options",n(t({},this.$store.state.options),t({},o))),this.$set(this.$store.state,"dynamicOptions",n(t({},this.$store.state.dynamicOptions),t({},s))),this.$set(this.$store.state,"currentPost",n(t({},this.$store.state.currentPost),t({},e))),this.$set(this.$store.state,"internalOptions",n(t({},this.$store.state.internalOptions),t({},a))),this.$set(this.$store.state,"tags",n(t({},this.$store.state.tags),t({},u)))}};export{y as S};