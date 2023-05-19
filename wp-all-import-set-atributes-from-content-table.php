<?php 




function prelucrare($tip, $rand, $data) {

	
	
		$match = explode("<tr>",$data);

	$match[$rand] = str_replace('<td>', '', $match[$rand]);
	$match[$rand] = str_replace('<tr>', '', $match[$rand]);
	$match[$rand] = str_replace('</tr>', '', $match[$rand]);
	

	$randu = explode("</td>",$match[$rand]);
	
	if($tip == "name") {
		return  ltrim(str_replace('<td>', '',$randu[0]));
	
	}
	
	if($tip == "value") {
		 return  ltrim(str_replace('<td>', '',$randu[1]));
	}


	
	


}
require_once(ABSPATH . 'wp-load.php');

add_action( 'template_redirect', 'returndata' );

function returndata($data) {




 $productAttributes=array();

        for ($i = 1; $i <= 10; $i++){
		
		
		$name = prelucrare('name',$i, $data);
		$valoare = prelucrare('value',$i, $data);
			$slug = 'pa_' . sanitize_title($name);
		
		if(!empty($name) &&  !empty($valoare)) {
							
				
			global $wpdb;

			// Numele atributului
$attribute_name = prelucrare('name',$i, $data);

// Valoarea atributului
$attribute_value = prelucrare('value',$i, $data);

// Verifică dacă atributul există deja în baza de date
$existing_attribute = wc_attribute_taxonomy_id_by_name($attribute_name);

// Dacă atributul nu există, îl înregistrează în baza de date
if (!$existing_attribute) {
    $attribute_id = wc_create_attribute(array(
        'name' => $attribute_name,
        'slug' => sanitize_title($attribute_name),
        'type' => 'select',
        'order_by' => 'menu_order',
        'has_archives' => true,
    ));
} else {
    $attribute_id = $existing_attribute;
}

// Actualizează valorile atributului
$term = term_exists($attribute_value, 'pa_' . sanitize_title($attribute_name));
if (!$term) {
    $term = wp_insert_term($attribute_value, 'pa_' . sanitize_title($attribute_name));
}
				
				
$productAttributes[$slug] = array(
							'name' => 'pa_' . sanitize_title($attribute_name),
							'value' => '',
							'position' => 0,
							'is_visible' => 1,
							'is_variation' => 1,
							'is_taxonomy' => '1'
						);
				
					


		}// end if emty data
		
			
    } // end for
          echo serialize($productAttributes);
 
die();
} // end f return data


// [prelucrare("name","1",{Content[1]})] si [prelucrare("value","1",{Content[1]})] @ atribute wp all import

//_product_attributes [returndata({Content[1]})]
?>
