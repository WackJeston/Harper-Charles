function setShowMarker(section) {
	$.ajax({
		url: "/functions-setShowMarker/" + section,
		type: "GET"
	});
};

function jumpToElement() {
	let id = location.href.split('#')[1];

	if (id != null) {
		let element = document.getElementById(id);
		element.scrollIntoView(false);
	}
};

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

function showDeleteWarning(type, id, url) {
	const warningZone = document.querySelector('.warning-overlay');
	const message = document.querySelector('.warning-overlay p');
	const deleteButton = document.querySelector('.warning-overlay .delete');
	const deleteLink = document.querySelector('.warning-overlay #delete-link');

	message.innerHTML = 'This will permanently delete <strong>' + type + ' #' + id + '</strong>';
	deleteLink.href = url;

	warningZone.style.display = 'flex';
};

function closeDeleteWarning() {
	const warningZone = document.querySelector('.warning-overlay');
	warningZone.style.display = 'none';
};