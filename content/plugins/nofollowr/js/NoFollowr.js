;(function($){
  
  $(function(){
    if (jbirchPlugin == undefined) {return false;}
    $(jbirchPlugin.postSelector).noFollowr(jbirchPlugin);
  });
  
  
  // apply controls to external links so admin can toggle nofollow easily
  $.fn.noFollowr = function(options) {
    var opts = $.extend({}, $.fn.noFollowr.defaults, options),
      canvasSupported;
      
      (function isCanvasSupported(){
        canvasSupported = !!document.createElement('canvas').getContext;
        if (canvasSupported) { $('html').addClass('nf-canvasSupported'); }
      })();
    
    // add utility expression to jQuery. Finds external links.
    $.expr[':'].external = function(obj){
      return !obj.href.match(/^mailto\:/) && (obj.hostname != location.hostname);
    };
    
    // loop through posts
    return this.each(function() {
      var $post = $(this),
        postid = $post.attr('id').split('-');
        
      if ( postid[1]==undefined ) { return false; }
      postid = postid[1];
      
      var $externals = $post.find('a:external'),
        getTitleAttr = function(status) {
          return 'Toggle link-love (currently "'+status+'"). Only admins see this.';
        },
        removeNofollow = function($link) {
          var rel = $link.attr('rel').replace(/\s?nofollow\s?/i,'');
          if ( rel == '' ) {
            $link.removeAttr('rel');
          } else {
            $link.attr('rel',rel);
          }
          return true;
        },
        addNofollow = function($link) {
          var rel = $link.attr('rel');
          if ( $.trim(rel) === '' ) {
            $link.attr('rel','nofollow');
          } else {
            $link.attr('rel',rel+' nofollow');
          }
        },
        setStatus = function(){
          var $$ = $(this),
            newStatus = 'follow';
            
          if ( $$.is('[rel~="nofollow"]') ) {
            removeNofollow($$);
          } else {
            addNofollow($$);
            newStatus = 'nofollow';
          }
          var moderator = $$.next('.nf-moderator');
            if (canvasSupported && moderator.data('throbber') === 'object' ) { moderator.data('throbber').stop(); }
            moderator.toggleClass('nf-nofollow')
            .removeClass('nf-loading')
            .html('<span>'+newStatus+'</span>')
            .attr('title', getTitleAttr(newStatus) );
        };
      
      $externals.each(function(){
        var $ext = $(this),
          extHref = $ext.attr('href'),
          status = ( $ext.is('[rel~="nofollow"]') ) ? 'nofollow' : 'follow',
          icon = $('<a>');
          
          icon.attr({
            href: '#',
            title: getTitleAttr(status)
          })
          .html('<span>'+status+'</span>')
          .addClass((status==='nofollow') ? 'nf-moderator nf-nofollow' : 'nf-moderator')
          .bind('click',function(){
            var clickee = $(this);
            if (clickee.is('.nf-loading')) { return false; }
            clickee.toggleClass('nf-loading');
            if (canvasSupported) {
              var throb = new $.fn.noFollowr.Throbber( clickee );
              throb.throb();
              clickee.data('throbber', throb );
            }
              
            $.post(
              opts.ajaxURL,
              {
                postid: postid,
                nofollow: (!$ext.is('[rel~="nofollow"]')),
                href: extHref
              },
              function(data){
                if (data==='success'){
                  $post.find('a[href="'+extHref+'"]').each(setStatus);
                } else {
                  alert('Apologies, but something seems to be amiss. Your link is probably unaltered.');
                }
              }
            );
            return false;
          });
          
        $ext.after(icon);
      });
    });//end each loop
  };//end NoFollowr
  
  // default options. This will help decouple this JS plugin from the WordPress plugin if necessary.
  $.fn.noFollowr.defaults = {
    ajaxURL : null
  };
  
  // slightly adapted from Alex Gawley at http://ablog.gawley.org/2009/05/randomness-throbbers-and-tag.html
  $.fn.noFollowr.Throbber = function(el) {
    this.options = {
      speedMS: 70,
      center: 2,
      thickness: 5,
      spokes: 7,
      color: [0,0,0],
      style: "balls" //set to "balls" for a different style of throbber
    };
    this.timer = -1;
    this.t = el;
    this.c = document.createElement('canvas');
    this.c.width = this.t.width();
    this.c.height = this.t.height();
    this.t.append(this.c);
    this.throb = function() {
      var ctx = this.c.getContext("2d");
      ctx.translate(this.c.width/2, this.c.height/2);
      var w = Math.floor(Math.min(this.c.width,this.c.height)/2);
      var self = this;
      var o = self.options;
      var draw = function() {
        ctx.clearRect(-self.c.width/2,-self.c.height/2,self.c.width,self.c.height);
        ctx.restore();
          for (var i = 0; i < o.spokes; i++) {
          r = 255-Math.floor((255-o.color[0]) / o.spokes * i);
          g = 255-Math.floor((255-o.color[1]) / o.spokes * i);
          b = 255-Math.floor((255-o.color[2]) / o.spokes * i);
            ctx.fillStyle = "rgb(" + r + "," + g + "," + b + ")";
          if(o.style == "balls") {
            ctx.beginPath();
            ctx.moveTo(w,0);
            ctx.arc(w-Math.floor(Math.PI*2*w/o.spokes/3),0,Math.floor(Math.PI*2*w/o.spokes/3),0,Math.PI*2,true);
            ctx.fill();
          } else { ctx.fillRect(o.center, -Math.floor(o.thickness/2), w-o.center, o.thickness); }
          ctx.rotate(Math.PI/(o.spokes/2));
          if(i == 0) { ctx.save(); } 
        }
        self.timer = setTimeout(draw,o.speedMS);
      };
      draw();
    };
    this.stop = function() {
      clearTimeout(this.timer);
      this.c.getContext("2d").clearRect(-this.c.width/2,-this.c.height/2,this.c.width,this.c.height);
      $(this.c).remove();
    };
  }; //end Throbber
  
  
})(jQuery);