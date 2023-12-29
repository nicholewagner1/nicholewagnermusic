<!--download-->

<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-1.4.3.min.js" ></script> -->

<script type="text/javascript" src="/wp-content/themes/encore_nicholewagner/jquery.mask.js" ></script>
<style>
	#download_code {font-size:16px; width:210px;}
	#downloadLink {margin:15px; background:lightgray;padding: 10px;
    border-radius: 10px;border:2px solid white;}
    #downloadLink:hover {border:2px solid #324f91; background:white;}
	p {font-size: smaller}
	</style>

<!--download codes are generated with code on plugins/coupon-generator-for-woocommerce/includes/admin/wccg-core-functions.php in line 130-->
<script type="text/javascript">

    jQuery(document).ready(function(){
  jQuery('#download_code').mask('AAAA-AAAA-AAAA', {'translation': {
    A: {pattern: /[A-Za-z0-9]/}
  }
	});

        jQuery("#download_code").keyup(function(){
              if (jQuery(this).val().length == 14) {
    jQuery('#downloadLink').fadeIn();
        jQuery('#albumArt').fadeIn();

couponValue = this.value;
var match = couponValue.match(/ASCF/i);
var matchCD = couponValue.match(/CDAS-CF/i);
var matchVinyl = couponValue.match(/VASC-F/i);
var matchPlasticFlowersVinyl = couponValue.match(/VPFS/i);

if(match) {
jQuery("#downloadLink").attr("href", "/checkout/?add-to-cart=2742&apply_coupon=" + jQuery(this).val());
console.log ('matches ASCF');
                jQuery("#albumArt").attr("src","/wp-content/uploads/2018/03/ATSCF_Cover_Square.jpg");

}
if(matchCD) {
jQuery("#downloadLink").attr("href", "/checkout/?add-to-cart=2959&apply_coupon=" + jQuery(this).val());
console.log ('matches ASCF-CD');
                jQuery("#albumArt").attr("src","/wp-content/uploads/2018/03/ATSCF_Cover_Square.jpg");


}
if(matchVinyl) {
                jQuery("#downloadLink").attr("href", "/checkout/?add-to-cart=6272&apply_coupon=" + jQuery(this).val());
                jQuery("#albumArt").attr("src","/wp-content/uploads/2018/03/ATSCF_Cover_Square.jpg");
                console.log ('matches ASCF-Vinyl');

}
if(matchPlasticFlowersVinyl) {
                jQuery("#downloadLink").attr("href", "/checkout/?add-to-cart=36926&apply_coupon=" + jQuery(this).val());
                console.log ('matches Plastic');
                jQuery("#albumArt").attr("src","/wp-content/uploads/PlasticFlowersCover2023-Large.jpeg");


}
else {  // alert(jQuery(this).val());
                jQuery("#downloadLink").attr("href", "/checkout/?add-to-cart=676&apply_coupon=" + jQuery(this).val());
						 }
		}
        });
        

    });
    
</script>
<div style="float:left; width:60%; text-align: center; padding: 0 20px;">
<p><input type="text" id="download_code" name="download_code" placeholder="Enter Download Card Code"  maxlength='15' />
<p>
	
</p>
<a href="#" id="downloadLink" style="display: none;">Redeem</a></p>
<p>Codes are *NOT* case sensitive.</p>
<p>Album downloads are .zip format and probably won't work on your mobile device, sorry.</p>
<p>Having trouble? Email <a href="mailto:contact@nicholewagnermusic.com">contact@nicholewagnermusic.com</a> for help.</p>

</div>
<div style="float:left;width:40%;min-height:400px">
	<img src="" id="albumArt">

</div>