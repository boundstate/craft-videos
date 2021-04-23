(function(e){function t(t){for(var o,r,s=t[0],c=t[1],d=t[2],u=0,p=[];u<s.length;u++)r=s[u],Object.prototype.hasOwnProperty.call(i,r)&&i[r]&&p.push(i[r][0]),i[r]=0;for(o in c)Object.prototype.hasOwnProperty.call(c,o)&&(e[o]=c[o]);l&&l(t);while(p.length)p.shift()();return a.push.apply(a,d||[]),n()}function n(){for(var e,t=0;t<a.length;t++){for(var n=a[t],o=!0,s=1;s<n.length;s++){var c=n[s];0!==i[c]&&(o=!1)}o&&(a.splice(t--,1),e=r(r.s=n[0]))}return e}var o={},i={explorer:0},a=[];function r(t){if(o[t])return o[t].exports;var n=o[t]={i:t,l:!1,exports:{}};return e[t].call(n.exports,n,n.exports,r),n.l=!0,n.exports}r.m=e,r.c=o,r.d=function(e,t,n){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},r.r=function(e){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"===typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)r.d(n,o,function(t){return e[t]}.bind(null,o));return n},r.n=function(e){var t=e&&e.__esModule?function(){return e["default"]}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="https://localhost:8090/";var s=window["webpackJsonp"]=window["webpackJsonp"]||[],c=s.push.bind(s);s.push=t,s=s.slice();for(var d=0;d<s.length;d++)t(s[d]);var l=c;a.push([1,"chunk-vendors"]),n()})({1:function(e,t,n){e.exports=n("57e4")},"57e4":function(e,t,n){"use strict";n.r(t);n("e260"),n("e6cf"),n("cca6"),n("a79d");var o=n("8bbf"),i=n.n(o),a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"h-full",attrs:{id:"videos"}},[n("div",{staticClass:"body has-sidebar"},[n("div",{staticClass:"content has-sidebar"},[e.loading?[n("div",{staticClass:"spinner"})]:[n("sidebar"),n("div",{staticClass:"main"},[n("search",{staticClass:"mb-6"}),e.videosLoading?[n("div",{staticClass:"spinner"})]:[n("videos",{attrs:{videos:e.videos}})]],2)]],2)])])},r=[],s=n("5530"),c=n("5880"),d=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"sidebar"},[n("div",{staticClass:"px-2"},[n("div",{staticClass:"select fullwidth"},[n("select",{directives:[{name:"model",rawName:"v-model",value:e.currentGatewayHandle,expression:"currentGatewayHandle"}],on:{change:function(t){var n=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){var t="_value"in e?e._value:e.value;return t}));e.currentGatewayHandle=t.target.multiple?n:n[0]}}},e._l(e.gateways,(function(t,o){return n("option",{key:"gateway-"+o,domProps:{value:t.handle}},[e._v(e._s(t.name))])})),0)])]),n("nav",[n("ul",[e.currentGateway?[e._l(e.currentGateway.sections,(function(t,o){return[n("li",{key:"section-"+o,staticClass:"heading"},[n("span",[e._v(e._s(t.name))])]),e._l(t.collections,(function(t,i){return[n("li",{key:"collection-"+o+"-"+i},[n("a",{class:{sel:e.isCollectionSelected(o,i)},attrs:{href:"#"},on:{click:function(n){return n.preventDefault(),e.handleCollectionClick(o,i,t)}}},[e._v(e._s(t.name))])])]}))]}))]:e._e()],2)])])},l=[],u={computed:Object(s["a"])(Object(s["a"])(Object(s["a"])({},Object(c["mapState"])({gateways:function(e){return e.gateways},selectedCollection:function(e){return e.selectedCollection}})),Object(c["mapGetters"])(["currentGateway"])),{},{currentGatewayHandle:{get:function(){return this.$store.state.currentGatewayHandle},set:function(e){this.$store.commit("updateCurrentGatewayHandle",e)}}}),methods:{handleCollectionClick:function(e,t,n){var o=this,i=this.getCollectionUniqueKey(this.currentGatewayHandle,e,t);this.$store.commit("updateSelectedCollection",i),this.$store.dispatch("getVideos",{gateway:this.currentGatewayHandle,method:n.method,options:n.options}).catch((function(){o.$store.dispatch("displayError","Couldn’t get videos.")}))},isCollectionSelected:function(e,t){return this.selectedCollection===this.getCollectionUniqueKey(this.currentGatewayHandle,e,t)}}},p=u,f=n("2877"),v=Object(f["a"])(p,d,l,!1,null,null,null),h=v.exports,y=function(){var e=this,t=e.$createElement,n=e._self._c||t;return e.currentGateway?n("div",[n("input",{directives:[{name:"model",rawName:"v-model",value:e.query,expression:"query"}],staticClass:"text fullwidth",attrs:{type:"search",placeholder:e.t("videos","Search {gateway} videos…",{gateway:e.currentGateway.name})},domProps:{value:e.query},on:{input:[function(t){t.target.composing||(e.query=t.target.value)},e.debouncedSearch],keyup:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"enter",13,t.key,"Enter")?null:e.search(t)}}})]):e._e()},g=[],m=(n("ac1f"),n("841c"),n("f7fe")),w=n.n(m),b={data:function(){return{query:""}},computed:Object(s["a"])(Object(s["a"])({},Object(c["mapGetters"])(["currentGateway"])),{},{debouncedSearch:function(){var e=this;return w()((function(){e.search()}),1e3)}}),methods:{search:function(){var e=this;this.debouncedSearch.cancel(),this.$store.commit("updateSelectedCollection",null),this.$store.dispatch("getVideos",{gateway:this.currentGateway.handle,method:"search",options:{q:this.query}}).catch((function(){e.$store.dispatch("displayError","Couldn’t get videos.")}))}}},C=b,V=Object(f["a"])(C,y,g,!1,null,null,null),_=V.exports,G=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"w-full grid grid-cols-3 grid gap-4"},[e._l(e.videos,(function(e,t){return[n("video-card",{key:"video-"+t,attrs:{video:e}})]}))],2)},k=[],O=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"group",on:{click:function(t){return e.selectVideo(e.video)},dblclick:function(t){return e.useVideo(e.video)}}},[n("div",{staticClass:"videos-thumb group-hover:ring group-hover:ring-red-500 group-hover:ring-opacity-80",class:[{"ring ring-red-500 ring-opacity-80":e.isVideoSelected}]},[n("img",{attrs:{src:e.video.thumbnail,alt:e.video.title}}),n("div",{staticClass:"play",on:{click:function(t){return e.play(e.video)}}})]),n("div",[e._v(e._s(e.video.title))])])},S=[],j={props:{video:{type:Object}},computed:{isVideoSelected:function(){return!!this.$store.state.selectedVideo&&this.$store.state.selectedVideo.id===this.video.id}},methods:Object(s["a"])(Object(s["a"])({},Object(c["mapActions"])(["selectVideo","updateVideoUrlWithSelectedVideo"])),{},{play:function(e){this.$root.eventBus.$emit("playVideo",{video:e})},useVideo:function(e){this.selectVideo(e),this.updateVideoUrlWithSelectedVideo(),this.$root.eventBus.$emit("useSelectedVideo")}})},x=j,$=Object(f["a"])(x,O,S,!1,null,null,null),H=$.exports,T={components:{VideoCard:H},props:{videos:{type:Array}}},U=T,E=Object(f["a"])(U,G,k,!1,null,null,null),q=E.exports,P={name:"videos-app",components:{Sidebar:h,Search:_,Videos:q},data:function(){return{loading:!1}},computed:Object(s["a"])({},Object(c["mapState"])({currentGatewayHandle:function(e){return e.currentGatewayHandle},gateways:function(e){return e.gateways},videos:function(e){return e.videos},videosLoading:function(e){return e.videosLoading}})),mounted:function(){var e=this;this.loading=!0,this.$store.dispatch("getGateways").then((function(){if(e.loading=!1,e.gateways.length>0){var t=e.gateways[0];e.$store.commit("updateCurrentGatewayHandle",t.handle);var n=t.sections[0].collections[0],o=e.getCollectionUniqueKey(t.handle,0,0);e.$store.commit("updateSelectedCollection",o),e.$store.dispatch("getVideos",{gateway:t.handle,method:n.method,options:n.options}).catch((function(){e.$store.dispatch("displayError","Couldn’t get videos.")}))}}))}},L=P,M=Object(f["a"])(L,a,r,!1,null,null,null),A=M.exports,F=n("a3a0"),K=n("ab54");i.a.config.productionTip=!1,i.a.mixin(K["a"]),window.VideoExplorerConstructor=i.a.extend({render:function(e){return e(A)},store:F["a"]})},5880:function(e,t){e.exports=Vuex},6955:function(e,t,n){"use strict";var o=n("cebe"),i=n.n(o);t["a"]={getGateways:function(){return i.a.get(Craft.getActionUrl("videos/vue/get-gateways"),{headers:{"X-CSRF-Token":Craft.csrfTokenValue}})},getVideos:function(e,t,n){var o={gateway:e,method:t};return n&&(o.options=n),i.a.post(Craft.getActionUrl("videos/vue/get-videos"),o,{headers:{"X-CSRF-Token":Craft.csrfTokenValue}})},getVideo:function(e){var t={url:e};return i.a.post(Craft.getActionUrl("videos/vue/get-video"),t,{headers:{"X-CSRF-Token":Craft.csrfTokenValue}})},getVideoEmbedHtml:function(e){var t={gateway:e.gatewayHandle,videoId:e.id};return i.a.post(Craft.getActionUrl("videos/vue/get-video-embed-html"),t,{headers:{"X-CSRF-Token":Craft.csrfTokenValue}})}}},"8bbf":function(e,t){e.exports=Vue},a3a0:function(e,t,n){"use strict";n("7db0");var o=n("6955");t["a"]={strict:!0,state:{videosLoading:!1,gateways:[],currentGatewayHandle:null,selectedCollection:null,selectedVideo:null,playingVideo:null,videos:[],videoUrl:null},getters:{currentGateway:function(e){return e?e.gateways.find((function(t){return t.handle===e.currentGatewayHandle})):null}},actions:{displayError:function(e,t){Craft.cp.displayError(t)},displayNotice:function(e,t){Craft.cp.displayNotice(t)},getGateways:function(e){var t=e.commit;return o["a"].getGateways().then((function(e){t("updateGateways",e)}))},getVideos:function(e,t){var n=e.commit,i=t.gateway,a=t.method,r=t.options;return n("updateVideosLoading",!0),o["a"].getVideos(i,a,r).then((function(e){n("updateVideosLoading",!1),n("updateVideos",{videos:e.data.videos,videosMore:e.data.videosMore,videosToken:e.data.videosToken})})).catch((function(e){throw n("updateVideosLoading",!1),n("updateVideos",{videos:[],videosMore:null,videosToken:null}),e}))},selectVideo:function(e,t){var n=e.commit;n("updateSelectedVideo",t)},updateVideoUrlWithSelectedVideo:function(e){var t=e.commit,n=e.state;if(!n.selectedVideo)return!1;t("updateVideoUrl",n.selectedVideo.url)}},mutations:{updateVideos:function(e,t){var n=t.videos,o=t.videosMore,i=t.videosToken;e.videos=n,e.videosMore=o,e.videosToken=i},updateGateways:function(e,t){e.gateways=t.data},updateCurrentGatewayHandle:function(e,t){e.currentGatewayHandle=t},updateVideosLoading:function(e,t){e.videosLoading=t},updateSelectedCollection:function(e,t){e.selectedCollection=t},updateSelectedVideo:function(e,t){e.selectedVideo=t},updatePlayingVideo:function(e,t){e.playingVideo=t},updateVideoUrl:function(e,t){e.videoUrl=t}}}},ab54:function(e,t,n){"use strict";t["a"]={methods:{getCollectionUniqueKey:function(e,t,n){return e+":"+t+":"+n},t:function(e,t,n){return Craft.t(e,t,n)}}}},cebe:function(e,t){e.exports=axios}});
//# sourceMappingURL=explorer.js.map