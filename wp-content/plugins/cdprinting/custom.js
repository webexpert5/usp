jQuery('#calculate-price').on('click',function(e){
	e.preventDefault();
	// lets get the price
		jQuery.ajax({
		  method: "POST",
		  url: "http://ec2-35-165-153-147.us-west-2.compute.amazonaws.com/api/omid/getprice.php"
		})
		  .done(function( response ) {
				console.log(response);
				var price	=	jQuery.parseJSON(response);
				jQuery('.price-box').val(price);
				jQuery('.us-printing-submit').show();
		  });		
		
});

