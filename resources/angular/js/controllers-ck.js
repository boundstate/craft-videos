function ServicesListCtrl(e,t,n,r,i,s,o){function a(e,i){typeof t.playlistId!="undefined"&&(e.playlistId=t.playlistId);o.loader.on();n({method:"POST",url:Craft.getActionUrl("videos/ajax/"+e.method,e)}).success(function(t,n,r,i){console.log("ajax/"+e.method+" : success")}).error(function(t,n,r,i){console.log("ajax/"+e.method+" : error",t,n,r,i)}).then(function(t,n,s){console.log("ajax/"+e.method+" : then");typeof i=="function"?i(t.data):r.videos=t.data;t.data.length<Dukt_videos.pagination_per_page?o.videoMore.off():o.videoMore.on();o.loader.off()})}console.log("controller",t.serviceKey,t.methodName);if(typeof t.serviceKey!="undefined"&&typeof t.methodName!="undefined"){var u={service:t.serviceKey,method:t.methodName,page:1,perPage:Dukt_videos.pagination_per_page};t.methodName!="search"&&a(u)}e.moreVideos=function(){var e=r.videos.length;o.videoMore.off();perPage=Dukt_videos.pagination_per_page;page=Math.floor(e/perPage)+1;var n={method:t.methodName,service:t.serviceKey,searchQuery:o.searchQuery,page:page,perPage:perPage};a(n,function(e){$.merge(r.videos,e)})};e.play=function(r){dkvideos.preview.play(r);e.selected=r;n({method:"POST",url:Craft.getActionUrl("videos/ajax/embed",{videoUrl:r.url,service:t.serviceKey})}).success(function(e,t,n,i){console.log("--success",$.parseJSON(e));$("#player .title").html(r.title);$("#player #videoDiv").html($.parseJSON(e))}).error(function(e,t,n,r){console.log("--error",e,t,n,r)});console.log("play video",r.id)};e.isSelected=function(t){return e.selected===t}};