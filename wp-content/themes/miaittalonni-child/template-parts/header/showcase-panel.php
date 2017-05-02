<?php
/**
 * Template part for showcase panel in header.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Miaittalonni
 */

$record = geoip_detect2_get_info_from_current_ip($locales = array('es'), $options = array());

function edicion($isoCode)
{
	switch ($isoCode) {

		case 'CMX': return "centro"; break;
		case 'DIF': return "centro"; break;
		case 'VER': return "centro"; break;
		case 'PUE': return "centro"; break;
		case 'TLA': return "centro"; break;
		case 'HID': return "centro"; break;
		case 'QUE': return "centro"; break;
		case 'MEX': return "centro"; break;
		case 'MOR': return "centro"; break;

		case 'CHH': return "noreste"; break;
		case 'COA': return "noreste"; break;
		case 'NLE': return "noreste"; break;
		case 'TAM': return "noreste"; break;
		case 'SLP': return "noreste"; break;
		case 'ZAC': return "noreste"; break;
		case 'DUR': return "noreste"; break;

		case 'JAL': return "occidente"; break;
		case 'AGU': return "occidente"; break;
		case 'COL': return "occidente"; break;
		case 'GUA': return "occidente"; break;
		case 'MIC': return "occidente"; break;
		case 'GRO': return "occidente"; break;

		case 'OAX': return "sureste"; break;
		case 'CHP': return "sureste"; break;
		case 'TAB': return "sureste"; break;
		case 'CAM': return "sureste"; break;
		case 'ROO': return "sureste"; break;
		case 'YUC': return "sureste"; break;

		case 'BCS': return "pacifico"; break;
		case 'BCN': return "pacifico"; break;
		case 'SON': return "pacifico"; break;
		case 'SIN': return "pacifico"; break;
		case 'NAY': return "pacifico"; break;

		default: return "centro"; break;
	}
}

$isoCode = $record->mostSpecificSubdivision->isoCode;

// $isoCode = "YUC";

$edi = edicion($isoCode);

?>


<div class="centercontainer">

	<div class="row">
		<div class="col-xs-12">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logosolo.png" class="logosolo" alt="">
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 text-center">
			<img class="etiqueta" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/edicion_<? echo $edi; ?>.png" alt="">
			<div class="eligeestado">
				<span class="listaestado">
					<select class="target" dir="rtl">
						<option id="option_AGU" <? echo ($isoCode === "AGU") ? "selected": ""; ?> value="AGU">Aguascalientes</option>
						<option id="option_BCN" <? echo ($isoCode === "BCN") ? "selected": ""; ?> value="BCN">Baja California</option>
						<option id="option_BCS" <? echo ($isoCode === "BCS") ? "selected": ""; ?> value="BCS">Baja California Sur</option>
						<option id="option_CAM" <? echo ($isoCode === "CAM") ? "selected": ""; ?> value="CAM">Campeche</option>
						<option id="option_CHP" <? echo ($isoCode === "CHP") ? "selected": ""; ?> value="CHP">Chiapas</option>
						<option id="option_CHH" <? echo ($isoCode === "CHH") ? "selected": ""; ?> value="CHH">Chihuahua</option>
						<option id="option_CMX" <? echo ($isoCode === "CMX") ? "selected": ""; ?> value="CMX">Ciudad de México</option>
						<option id="option_COA" <? echo ($isoCode === "COA") ? "selected": ""; ?> value="COA">Coahuila</option>
						<option id="option_COL" <? echo ($isoCode === "COL") ? "selected": ""; ?> value="COL">Colima</option>
						<option id="option_DUR" <? echo ($isoCode === "DUR") ? "selected": ""; ?> value="DUR">Durango</option>
						<option id="option_MEX" <? echo ($isoCode === "MEX") ? "selected": ""; ?> value="MEX">Estado de México</option>
						<option id="option_GUA" <? echo ($isoCode === "GUA") ? "selected": ""; ?> value="GUA">Guanajuato</option>
						<option id="option_GRO" <? echo ($isoCode === "GRO") ? "selected": ""; ?> value="GRO">Guerrero</option>
						<option id="option_HID" <? echo ($isoCode === "HID") ? "selected": ""; ?> value="HID">Hidalgo </option>
						<option id="option_JAL" <? echo ($isoCode === "JAL") ? "selected": ""; ?> value="JAL">Jalisco </option>
						<option id="option_MIC" <? echo ($isoCode === "MIC") ? "selected": ""; ?> value="MIC">Michoacán</option>
						<option id="option_MOR" <? echo ($isoCode === "MOR") ? "selected": ""; ?> value="MOR">Morelos</option>
						<option id="option_NAY" <? echo ($isoCode === "NAY") ? "selected": ""; ?> value="NAY">Nayarit</option>
						<option id="option_NLE" <? echo ($isoCode === "NLE") ? "selected": ""; ?> value="NLE">Nuevo León</option>
						<option id="option_OAX" <? echo ($isoCode === "OAX") ? "selected": ""; ?> value="OAX">Oaxaca</option>
						<option id="option_PUE" <? echo ($isoCode === "PUE") ? "selected": ""; ?> value="PUE">Puebla </option>
						<option id="option_QUE" <? echo ($isoCode === "QUE") ? "selected": ""; ?> value="QUE">Querétaro</option>
						<option id="option_ROO" <? echo ($isoCode === "ROO") ? "selected": ""; ?> value="ROO">Quintana Roo</option>
						<option id="option_SLP" <? echo ($isoCode === "SLP") ? "selected": ""; ?> value="SLP">San Luis Potosí</option>
						<option id="option_SIN" <? echo ($isoCode === "SIN") ? "selected": ""; ?> value="SIN">Sinaloa</option>
						<option id="option_SON" <? echo ($isoCode === "SON") ? "selected": ""; ?> value="SON">Sonora</option>
						<option id="option_TAB" <? echo ($isoCode === "TAB") ? "selected": ""; ?> value="TAB">Tabasco</option>
						<option id="option_TAM" <? echo ($isoCode === "TAM") ? "selected": ""; ?> value="TAM">Tamaulipas</option>
						<option id="option_TLA" <? echo ($isoCode === "TLA") ? "selected": ""; ?> value="TLA">Tlaxcala</option>
						<option id="option_VER" <? echo ($isoCode === "VER") ? "selected": ""; ?> value="VER">Veracruz</option>
						<option id="option_YUC" <? echo ($isoCode === "YUC") ? "selected": ""; ?> value="YUC">Yucatán</option>
						<option id="option_ZAC" <? echo ($isoCode === "ZAC") ? "selected": ""; ?> value="ZAC">Zacatecas</option>
					</select>
				</span>
				<i class="cosin fa fa-sort-desc" aria-hidden="true"></i>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-12 text-center" style="padding-top: 20px;">
			<a class="btn-participar btn btn-primary showcase-panel__btn" style="font-size: 19px!important;" href="https://<?php echo $edi; ?>.presumetusalsa.com/app?estado=<?php echo $isoCode; ?>">Participar</a>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<h3 class="ganarias">¡PUEDES GANAR HASTA $100,000!</h3>
		</div>
	</div>

</div>

<script>

	function miestado(abr) {
		switch(abr){
			case "B.C.S.": return "BCS"; break;
			case "B.C.":   return "BCN"; break;
			case "Son.":   return "SON"; break;
			case "Chih.":  return "CHH"; break;
			case "Coah.":  return "COA"; break;
			case "Tamps.": return "TAM"; break;
			case "Nay.":   return "NAY"; break;
			case "Sin.":   return "SIN"; break;
			case "Ags.":   return "AGU"; break;
			case "Jal.":   return "JAL"; break;
			case "Zac.":   return "ZAC"; break;
			case "S.L.P.": return "SLP"; break;
			case "Qro.":   return "QUE"; break;
			case "Mich.":  return "MIC"; break;
			case "CDMX":   return "CMX"; break;
			case "Méx.":   return "MEX"; break;
			case "Mor.":   return "MOR"; break;
			case "Col.":   return "COL"; break;
			case "Hgo.":   return "HID"; break;
			case "Tlax.":  return "TLA"; break;
			case "Pue.":   return "PUE"; break;
			case "Ver.":   return "VER"; break;
			case "Gro.":   return "GRO"; break;
			case "Oax.":   return "OAX"; break;
			case "Chis.":  return "CHP"; break;
			case "Camp.":  return "CAM"; break;
			case "Tab.":   return "TAB"; break;
			case "Yuc.":   return "YUC"; break;
			case "Q.R.":   return "ROO"; break;
			case "N.L.":   return "NLE"; break;
			case "Dgo.":   return "DUR"; break;
			case "Gto.":   return "GUA"; break;
			case "Praga":  return "SLP"; break;
			default:       return "OTR"; break;
		}
	}

	function miedicion(mycode)
	{
		switch (mycode) {
			case 'CMX': return "centro"; break;
			case 'DIF': return "centro"; break;
			case 'VER': return "centro"; break;
			case 'PUE': return "centro"; break;
			case 'TLA': return "centro"; break;
			case 'HID': return "centro"; break;
			case 'QUE': return "centro"; break;
			case 'MEX': return "centro"; break;
			case 'MOR': return "centro"; break;

			case 'CHH': return "noreste"; break;
			case 'COA': return "noreste"; break;
			case 'NLE': return "noreste"; break;
			case 'TAM': return "noreste"; break;
			case 'SLP': return "noreste"; break;
			case 'ZAC': return "noreste"; break;
			case 'DUR': return "noreste"; break;

			case 'JAL': return "occidente"; break;
			case 'AGU': return "occidente"; break;
			case 'COL': return "occidente"; break;
			case 'GUA': return "occidente"; break;
			case 'MIC': return "occidente"; break;
			case 'GRO': return "occidente"; break;

			case 'OAX': return "sureste"; break;
			case 'CHP': return "sureste"; break;
			case 'TAB': return "sureste"; break;
			case 'CAM': return "sureste"; break;
			case 'ROO': return "sureste"; break;
			case 'YUC': return "sureste"; break;

			case 'BCS': return "pacifico"; break;
			case 'BCN': return "pacifico"; break;
			case 'SON': return "pacifico"; break;
			case 'SIN': return "pacifico"; break;
			case 'NAY': return "pacifico"; break;

			default: return "ninguno"; break;
		}
	}

	jQuery(function() {
	
		var isoCode = "<?php echo $isoCode; ?>"
		var edicion = "<?php echo $edi; ?>"
		var imagePath = "<?php echo get_stylesheet_directory_uri(); ?>"+"/assets/images/edicion_"
		var link = ".presumetusalsa.com/app?estado="

		jQuery( ".target" ).change(function() {
			jQuery(".target option:selected" ).each(function() {
		    	isoCode = jQuery(this).val()
		    	edicion = miedicion(isoCode)
		    	nuevaImagen = imagePath+edicion+".png"
		    	nuevoLink = "https://"+edicion+link+isoCode

		    	if (edicion === "pacifico") {nuevoLink = "https://pacifico.presumetusalsa.com"} // Pacífico debe dirigir al home

		    	jQuery(".etiqueta").attr("src", nuevaImagen) // Cambiar la imagen del listón
		    	jQuery(".btn-participar").attr("href", nuevoLink) // Cambiar href del botón
		    })
		})

		// Detección de ubicación
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(pos) {

				var url = "https://maps.googleapis.com/maps/api/geocode/json?latlng="+pos.coords.latitude+","+pos.coords.longitude+"&key=AIzaSyCYXDjUpZJ5n0AmICecpHJrdSPvNhM7Pgs"
				
				jQuery.get(url, function(data) {

					if (data.status === "OK") {
						loop1:
						for (var i = 0;  i<data.results.length; i++) {
							for (var j = 0;  j<data.results[i].address_components.length; j++) {
								for (var k = 0;  k<data.results[i].address_components[j].types.length; k++) {
									if (data.results[i].address_components[j].types[k] === "administrative_area_level_1") {
										var nuevoestado = miestado(data.results[i].address_components[j].short_name)
										var nuevaedicion = miedicion(nuevoestado)
										console.log(nuevoestado, nuevaedicion)
										break loop1;
									}
								}
							}
						}
					}
				})

			}, function(err) {
				console.log(err)
			}, {maximumAge: 90000})
		}
		else {
			console.log("No activado")
		}
		

	})
</script>


