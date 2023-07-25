function updateAddressMap() {
	let query = document.getElementById('line1').value.replace(',', '');
	query += ' ' + document.getElementById('line2').value.replace(',', '');
	query += ' ' + document.getElementById('line3').value.replace(',', '');
	query += ' ' + document.getElementById('city').value.replace(',', '');
	query += ' ' + document.getElementById('postcode').value.replace(',', '');

	query = encodeURI(query);

	$.ajax({
		url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + query + '&key=AIzaSyAOjxjL_XlAPigkAVGLxqHBSGaUJrMCszg',
		type: "GET",
		success: function(result) {
			uploadLatLng(result.results[0].geometry.location.lat, result.results[0].geometry.location.lng);
		}
	});
};

function uploadLatLng(lat, lng) {
	$.ajax({
		url: '/contactUploadLatLng/' + lat + '/' + lng,
		type: "GET",
		success: function(result) {
			setTimeout(() => {
			  document.getElementById('updateAddressMap').submit();
			}, 10);
		}
	});
};