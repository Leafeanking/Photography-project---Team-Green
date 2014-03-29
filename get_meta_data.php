<?php
if(isset($_GET['imageID'])){
	include_once('secure.php');
	include_once('functions.php');

	//works with jpeg or tiff
	function cameraUsed($imagePath) {
		
		// There are 2 arrays which contains the information we are after, so it's easier to state them both
		$exif_ifd0 = read_exif_data($imagePath ,'IFD0' ,0);       
		$exif_exif = read_exif_data($imagePath ,'EXIF' ,0);
		
		//error control
		$notFound = "Unavailable";
		
		// Make 
		if (@array_key_exists('Make', $exif_ifd0)) {
		$camMake = $exif_ifd0['Make'];
		} else { $camMake = $notFound; }
		
		// Model
		if (@array_key_exists('Model', $exif_ifd0)) {
		$camModel = $exif_ifd0['Model'];
		} else { $camModel = $notFound; }
		
		// Exposure
		if (@array_key_exists('ExposureTime', $exif_ifd0)) {
		$camExposure = $exif_ifd0['ExposureTime'];
		} else { $camExposure = $notFound; }
		
		// Aperture
		if (@array_key_exists('ApertureFNumber', $exif_ifd0['COMPUTED'])) {
		$camAperture = $exif_ifd0['COMPUTED']['ApertureFNumber'];
		} else { $camAperture = $notFound; }
		
		// Date
		if (@array_key_exists('DateTime', $exif_ifd0)) {
		$camDate = $exif_ifd0['DateTime'];
		} else { $camDate = $notFound; }
		
		// ISO
		if (@array_key_exists('ISOSpeedRatings',$exif_exif)) {
		$camIso = $exif_exif['ISOSpeedRatings'];
		} else { $camIso = $notFound; }
		
		// Shutter Speed
		if (@array_key_exists('ShutterSpeedValue',$exif_exif)) {
		$camShutter = $exif_exif['ShutterSpeedValue'];
		} else { $camShutter = $notFound; }
		
		$return = array();
		$return['make'] = $camMake;
		$return['model'] = $camModel;
		$return['exposure'] = $camExposure;
		$return['aperture'] = $camAperture;
		$return['date'] = $camDate;
		$return['iso'] = $camIso;
		$return['shutter'] = $camShutter;
		return $return;
	}
	$id = $_GET['imageID'];
	$results = dbGet("select data from images where imageID = $id");
	if(mysql_num_rows($results) != 0){
		$data = mysql_fetch_assoc($results);
		$string = $data['data'];
		$meta = cameraUsed("data://text/plain;base64,".base64_encode($string));
		
		$make 		= $meta['make'];
		$model		=$meta['model'];
		$exposure	=$meta['exposure'];
		$aperture	=$meta['aperture'];
		$date		=$meta['date'];
		$iso		=$meta['iso'];
		$shutter	=$meta['shutter'];
		
		echo "Make: $make || Model: $model || Exposure: $exposure || Aperture: $aperture || Date: $date || Iso: $iso || Shutter: $shutter";
	}
}

?>