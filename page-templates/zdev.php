<?php
/* Template Name: zDev */
$key = 'AIzaSyB4yJ0yV4fVKIDLBLTMCaHWO5SMYKBEmZE';
 get_header(); ?>
<?php // echo '<!DOCTYPE html><html>'; ?>



	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
    <input id="pac-input" class="controls" type="text" placeholder="Search Box" style="width:400px">
    <div id="map" style="height:300px"></div>

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
						<div class="entry-thumbnail">
							<?php the_post_thumbnail(); ?>
						</div>
						<?php endif; ?>

						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentythirteen' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
					</div><!-- .entry-content -->

					<footer class="entry-meta">
						<?php edit_post_link( __( 'Edit', 'twentythirteen' ), '<span class="edit-link">', '</span>' ); ?>
					</footer><!-- .entry-meta -->
				</article><!-- #post -->

				<?php comments_template(); ?>
			<?php endwhile; ?>

		</div><!-- #content -->
	</div><!-- #primary -->
     <script>

      // This example adds a search box to a map, using the Google Place Autocomplete
      // feature. People can enter geographical searches. The search box will return a
      // pick list containing a mix of places and predicted search terms.

      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 0.8688, lng: 0.2195},
          zoom: 2,
          mapTypeId: 'roadmap'
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }
          console.log(places);
          console.log(places[0]);

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            if(place.photos) {
              console.log("photos");
              let img = place.photos[0];
              console.log(img);
              var imgUrl = img.getUrl({'maxWidth': 400, 'maxHeight': 400, 'minWidth': 100, 'minHeight': 100});
              console.log(imgUrl);


            }

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });
      }


      let a = 'https://maps.googleapis.com/maps/api/place/js/PhotoService.GetPhoto?1sCmRaAAAAcxuI-CEJzSdehLCwroSO6UhoPsMUwllpGH9zghmXa8FfyQ-yBPYZNGwdLn6omDcentoV7ORqSzGQAC8wpg8V9hMBExT_--XrPWyL4fwOWnMagna5EZB8rbm8Q65xLvlGEhCezkwQwKa7oirDcuzHv_wCGhSa6UBkpu_iEvDv96OP2YZjwcxUYQ&3u400&4u400&5m1&2e1&callback=none&key=AIzaSyB4yJ0yV4fVKIDLBLTMCaHWO5SMYKBEmZE&token=79808';


    </script>
<img src="https://lh3.googleusercontent.com/p/AF1QipNQ-RZBIoR21vSumj5eSuv486PDKg0RldMQwuhi=s1600-w400-h400">

     <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $key ?>&libraries=places&callback=initAutocomplete"
        async defer></script>
<?php // get_sidebar(); ?>
<?php // get_footer(); ?>
<?php echo '</body></html>'; ?>
