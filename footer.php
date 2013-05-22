<footer id="colophon">
	<div class="container">
		<div class="three columns">
			<nav id="footer-nav">
				<?php wp_nav_menu(array('theme_location' => 'footer_menu')); ?>
			</nav>
		</div>
		<div class="five columns">
			<div class="credits">
				<span class="credits-title">Apoio</span>
				<a href="http://culturainglesasp.com.br/" rel="external" target="_blank" title="Cultura Inglesa"><img alt="Cultura Inglesa" src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo-cultura.png" /></a>
				<a href="http://cultura.gov.br/" rel="external" target="_blank" title="Ministério da Cultura"><img alt="Ministério da Cultura" src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo-minc.png" /></a>
			</div>
		</div>
		<div class="one column">
			<div class="cc">
				<p xmlns:dct="http://purl.org/dc/terms/" xmlns:vcard="http://www.w3.org/2001/vcard-rdf/3.0#">
					<a rel="license" href="http://creativecommons.org/publicdomain/zero/1.0/">
						<img src="http://i.creativecommons.org/p/zero/1.0/88x31.png" style="border-style: none;" alt="CC0" class="scale-with-grid" />
					</a>
					<span class="text">
						To the extent possible under law,
						<a rel="dct:publisher"
						href="http://arteforadomuseu.com.br/">
						<span property="dct:title">Arte Fora do Museu</span></a>
						has waived all copyright and related or neighboring rights to
						<span property="dct:title">Arte Fora do Museu</span>.
						This work is published from:
						<span property="vcard:Country" datatype="dct:ISO3166"
						content="BR" about="http://arteforadomuseu.com.br/">
						Brasil</span>.
					</span>
				</p>
			</div>
		</div>
		<div class="three columns">
			<div class="social">
				<div class="social-links">
					<div class="social-container">
						<div class="social-content">
							<a href="https://www.facebook.com/pages/Arte-Fora-do-Museu/152356688151751?fref=ts" class="facebook lsf" rel="external" target="_blank">&#xE047;</a>
							<a href="http://instagram.com/arteforadomuseu" class="instagram lsf" rel="external" target="_blank">&#xE155;</a>
							<a href="https://twitter.com/arteforadomuseu" class="twitter lsf" rel="external" target="_blank">&#xE12f;</a>
							<a href="http://www.youtube.com/arteforadomuseu" class="youtube lsf" rel="external" target="_blank">&#xE141;</a>
						</div>
					</div>
					<span class="social-toggler lsf">&#xE118;</span>
				</div>
				<div class="social-interaction">
					<a href="https://twitter.com/arteforadomuseu" class="twitter-follow-button" data-show-count="false" data-lang="pt" data-show-screen-name="false">Seguir @arteforadomuseu</a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
					<div class="fb-like" data-href="https://www.facebook.com/pages/Arte-Fora-do-Museu/152356688151751" data-send="false" data-layout="button_count" data-width="450" data-show-faces="true" data-font="verdana" data-colorscheme="dark"></div>
				</div>
			</div>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>