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
			$slug = 'pa_' . wc_sanitize_taxonomy_name(stripslashes($name));
		
		if(!empty($name) &&  !empty($valoare)) {
							
				
			global $wpdb;

			// Numele atributului
$attribute_name = $name;

// Valoarea atributului
$attribute_value = $valoare;

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
$term = term_exists($attribute_value, 'pa_' . $attribute_name);
if (!$term) {
    $term = wp_insert_term($attribute_value, 'pa_' . $attribute_name);
}

// Adaugă termenul atributului la produsul dorit
$product_id = 215; // ID-ul produsului
//wp_set_object_terms($product_id, $term['term_id'], 'pa_' . $attribute_name, true);
			 
				
				

				$productAttributes[$slug] = array(
							'name' => $name,
							'value' => $valoare,
							'position' => 1,
							'is_visible' => 1,
							'is_variation' => 1,
							'is_taxonomy' => '1'
						);
					


		}// end if emty data
		
			
    } // end for
          // print_r($productAttributes);
 echo serialize($productAttributes);

} // end f return data




?>
