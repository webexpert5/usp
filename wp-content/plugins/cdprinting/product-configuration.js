/** Lets create a solid base for managing different control flow **/

/** Main Class/Object here**/
function CD_Configure_Product(){
	this.config						=	USP_PRODUCT.config;
	this.formFields					=	USP_PRODUCT.keys;
	this.SelectedValues				=	{};
	this.matchedOptions				=	'';
	this.loadNextField				=	function(currentObj){										
											this.startLoader();
											this.SelectedValues[currentObj.data('key')]=currentObj.val();
											if(this.isLast(currentObj)){
												if(currentObj.val()!=''){
												this.getPrice();
												}else{
													this.removePrice();
													this.disableCartBtn();
													jQuery('#catalogs_upload').hide();
												}
												this.endLoader();
											}else{
												// Check if next element which is to be populated is disabled
												if(this.isNextDisabled(currentObj)){
													// find next dropdown and reset stop here
													setTimeout(function(classObj){ 
														classObj.fetchOptions(currentObj);
														classObj.endLoader();
													}  , 1000,this);
												}else{
													var NextSelectedValue	=	this.isAlreadySelected(currentObj);
													if(NextSelectedValue && NextSelectedValue!=''){
														setTimeout(function(classObj){ 
														classObj.fetchOptions(currentObj);
														nextKey	=	classObj.getNextKey(currentObj);
														// (find what, dropdown key)
														if(classObj.isValueValid(NextSelectedValue,nextKey)){
															jQuery('#option_id-'+nextKey).val(NextSelectedValue).change();
														}else{
															// Do nothing, recursion stopped. Disable upcoming
															classObj.disableFollowing(currentObj);
														}
													},1000,this);
														
													}else{
														setTimeout(function(classObj){ 
															classObj.fetchOptions(currentObj);
														}  , 1000,this);
													}
												}
											}
										}
	this.isLast						=	function(currentObj){
											if(this.formFields.slice(-1)[0]==currentObj.data('key')){
												return true;
											}
										}
	this.isNextDisabled					=	function(currentObj){	
											nextKey	=	this.getNextKey(currentObj);
											return jQuery('#option_id-'+nextKey).prop("disabled");
										}
	this.isValueValid					=	function(findWhat,key){
												return this.matchedOptions.find(function(match){
													return match==findWhat;
												});
												

										}
								
	this.fetchOptions				=	function(currentObj){
											keyName		=	currentObj.data('key');
											nextKey	=	this.getNextKey(currentObj);
											 if(currentObj.data('serial')>1){	
												var tempKey		=	'';
												var conditionBuilder	=	{};
												var stringTest			=	'';
												for (i = 1; i <= currentObj.data('serial'); i++) {
													tempKey			=	jQuery("[data-serial='" + i +"']").data('key');
													conditionBuilder[tempKey]	=	jQuery('#option_id-'+tempKey).val();
													stringTest	+=	'$._id.'+tempKey+"=='"+jQuery('#option_id-'+tempKey).val()+"' && ";
												}				
												stringTest=stringTest.slice(0,-3);
												queryResult = Enumerable.From(this.config).Where(stringTest).OrderBy(function (x) { return x._id[nextKey]; }).Select(function (x) { return x._id[nextKey] }).Distinct().ToArray();
												
											 }else{
													queryResult = Enumerable.From(this.config).Where(function (x) {
													 return x['_id'][keyName] == currentObj.val();										
												}).OrderBy(function (x) { return x._id[nextKey]; }).Select(function (x) { return x._id[nextKey] }).Distinct().ToArray();
											}
												optonKeysTemp			=	this.createNumericKeys(queryResult);
												queryResult				=	this.sortObjectByKey(optonKeysTemp);
												jQuery('#option_id-'+nextKey).attr('disabled',false);
												jQuery('#option_id-'+nextKey).empty();
												this.matchedOptions	=	queryResult;
												jQuery('#option_id-'+nextKey).append(jQuery('<option>',{
													value:'',
													text:'Choose An Option..'
												})); 
												jQuery.each(queryResult, function (i, item) {
													jQuery('#option_id-'+nextKey).append(jQuery('<option>', { 
														value: item,
														text : item 
													})); 
												});
												this.endLoader();
										}
	this.isAlreadySelected			=	function(currentObj){
											nextKey	=	this.getNextKey(currentObj);
											if(jQuery('#option_id-'+nextKey).val()!=''){
												return jQuery('#option_id-'+nextKey).val();
											}else{
												return false;
											}
										}
	this.getNextKey					=	function(currentObj){
											// No need to check isLast as we have already checked it in the entry point
											return this.formFields[this.formFields.indexOf(currentObj.data('key'))+1];
										}
	this.startLoader				=	function(){	
											jQuery('.USP-loading-aim ').removeClass('hidden');
										}
	this.endLoader					=	function(){		
											jQuery('.USP-loading-aim ').addClass('hidden');
										}
	this.disableFollowing			=	function(currentObj){
											for(var i=this.formFields.indexOf(currentObj.data('key'))+1;i<=this.formFields.length;i++){
												jQuery('#option_id-'+this.formFields[i]).empty();
												jQuery('#option_id-'+this.formFields[i]).attr('disabled',true);
											}
											jQuery('.USP-product-price').html('');
											this.disableCartBtn();
											this.endLoader();
										}
	this.getPrice					=	function(){
											var	dataObj	=	{};
											var str		=	'';
											var price	=	0.00;
											str+='filter[where][sku]'+'='+encodeURIComponent(jQuery('#usp-sku').val())+'&';
											jQuery.each(this.SelectedValues,function(key,val){
												//dataObj['filter[where]['+key+']']=encodeURIComponent(val);
												str+='filter[where]['+key+']'+'='+encodeURIComponent(val)+'&'
											});
											str=str.slice(0,-1);
											jQuery.ajax({
											  method: "GET",
											  url: "http://138.68.1.67:3000/api/productsflats/findOne?"+str,
											  
											})
											  .done(function( msg ) {
													price				=	parseFloat(msg.price.replace(/[\$,]/gi,''));
													price				=	price.toFixed(2);
													jQuery('.USP-product-apionly-price').html(price);
													/* If product has variations then we need to add the price */
													if(jQuery('.woocommerce-variation-price .woocommerce-Price-amount.amount').length>0){
														var variabtionPrice		=	jQuery('.woocommerce-variation-price .woocommerce-Price-amount.amount').text();
														variabtionPrice		=	variabtionPrice.replace('$','');
														
														price					=	parseFloat(price) + parseFloat(variabtionPrice);
														
													}													
													jQuery('.USP-product-price').html('<span>Printing Cost</span> $'+price);
													
													jQuery('.USP-product-api-price')
													obj.enableCartBtn();
													jQuery('#catalogs_upload').show();
													jQuery('.cd-product-field-wrapper').addClass('heightlight-added');

											  });
										}
	this.removePrice					=	function(){	
											// set price in some div
											//alert(price);
											jQuery('.USP-product-price').html('');
											
										}
	this.createNumericKeys				=	function(arr){
												nonInt=1;
												var obj		=	{};
												arr.forEach(function(val){
													num=parseFloat(val);
													if(isNaN(num)){
														num=nonInt-0.001;
														nonInt=num
													}
													if(num in obj){
														num=num+0.1;
													}
													obj[num]=val;
												});
												return obj;
										}
	this.sortObjectByKey 				= 	function(obj){
												var keys = [];
												var sorted_arr = [];

												for(var key in obj){
													if(obj.hasOwnProperty(key)){
														keys.push(parseFloat(key));
													}
												}
												// sort keys
												keys.sort(function(a, b){return a-b});

												// create new array based on Sorted Keys
												jQuery.each(keys, function(i, key){
													sorted_arr.push(obj[key]);
												});
												return sorted_arr;
											};										
	this.enableCartBtn				=	function(){
											jQuery('.cart .single_add_to_cart_button').attr('disabled',false);
										}
	this.disableCartBtn				=	function(){
											jQuery('.cart .single_add_to_cart_button').attr('disabled',true);
										}
	this.disableNext				=	function(){
											// also call this.disableCartBtn();
										}
}


jQuery(document).ready(function(){
	jQuery('.cart .single_add_to_cart_button').attr('disabled',true);
	
});
if(jQuery('.codedrill-additional-fields').length>0){
			$variation_form			=		jQuery('.variations');
			jQuery('.variations').remove();
			jQuery('.codedrill-additional-fields').append($variation_form);
	}
jQuery( ".single_variation_wrap" ).on( "show_variation", function ( event, variation ) {
    var CDvariationPrice					=	variation.display_price;
	var CDapiPrice 							=	jQuery('.USP-product-apionly-price').text();
	var CDtotalPrice						=	parseFloat(CDvariationPrice)+parseFloat(CDapiPrice);
	jQuery('.USP-product-price').html('<span>Printing Cost</span> $'+CDtotalPrice);
} );	
/** Main Class/Object ends here **/
	var obj= new CD_Configure_Product();
/** Attach Event **/

jQuery("select.us-printing-common-fields").on("change",function(){

	obj.loadNextField(jQuery(this));
});


	
	/*jQuery('.box-items').on('click',function(){
		obj.loadNextField(jQuery(this).children());
		});*/


    //jQuery(".single_add_to_cart_button").click(function(event) {
       // if (!jQuery(event.target).is('select.us-printing-common-fields')) {
      // jQuery('#option_id-Size').simulate('click');
      //}
      
    //});
    
  
