{{-- <section id="photoViewer"> --}}
<div id="pswp" class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

	<!-- Background of PhotoSwipe. 
		 It's a separate element as animating opacity is faster than rgba(). -->
	<div class="pswp__bg"></div>

	<!-- Slides wrapper with overflow:hidden. -->
	<div class="pswp__scroll-wrap">

		<!-- Container that holds slides. 
			PhotoSwipe keeps only 3 of them in the DOM to save memory.
			Don't modify these 3 pswp__item elements, data is added later on. -->
		<div class="pswp__container">
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
		</div>

		<div class="pswp__info">
			
		</div>

		<!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
		<div class="pswp__ui pswp__ui--hidden">

			<div class="pswp__top-bar">

				<!--  Controls are self-explanatory. Order can be changed. -->

				<div class="pswp__counter"></div>

				<button class="pswp__button pswp__button--close" title="{{ trans('aria.photos.close') }} (Esc)">
					<i class="fa fa-times" aria-hidden="true"></i>
				</button>

				<button class="pswp__button pswp__button--download" title="{{ trans('aria.photos.download') }}">
					<i class="fa fa-download" aria-hidden="true"></i>
				</button>

				<button class="pswp__button pswp__button--info" title="{{ trans('aria.photos.info') }}">
					<i class="fa fa-info-circle" aria-hidden="true"></i>
				</button>

				<button class="pswp__button pswp__button--album" title="{{ trans('aria.photos.gotoalbum') }}">
					<i class="fa fa-folder-open-o" aria-hidden="true"></i><span class="noselect viewer-album">Album</span>
				</button>

				<!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
				<!-- element will get class pswp__preloader--active when preloader is running -->
				<div class="pswp__preloader">
					<div class="pswp__preloader__icn">
					  <div class="pswp__preloader__cut">
						<div class="pswp__preloader__donut"></div>
					  </div>
					</div>
				</div>
			</div>

			<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
				<div class="pswp__share-tooltip"></div> 
			</div>

			<button class="pswp__button pswp__button--arrow--left" title="{{ trans('pagination.previous') }}">
				<i class="fa fa-chevron-left" aria-hidden="true"></i>
			</button>

			<button class="pswp__button pswp__button--arrow--right" title="{{ trans('pagination.next') }}">
				<i class="fa fa-chevron-right" aria-hidden="true"></i>
			</button>

			<div class="pswp__caption">
				<div class="pswp__caption__center"></div>
			</div>

		</div>

	</div>

</div>
{{-- </section> --}}