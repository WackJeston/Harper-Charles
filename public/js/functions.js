function loadingScreen() {
	let target = document.querySelector('#loading-screen');

	target.classList.add('transition1');

	setTimeout(() => {
		target.querySelector('img').classList.add('remove');
	}, 1500);

	setTimeout(() => {
		target.classList.add('transition2');
	}, 1800);

	setTimeout(() => {
		target.classList.add('remove');
	}, 2400);
};

async function load3dModel(key, id) {
	try {
		this.expiviInstance = new ExpiviComponent.default({
			catalogueId: id,
			viewerContainer: '#viewerContainer',
			optionContainer: '#optionsContainer',
			priceSelectors: '#priceContainer',
			currency: 'GBP',
			locale: 'en',
			token: key,
			// preset: {preset}
		});
	} catch (error) {
		console.log(error);
	}
};

async function setShowMarker(section) {
	await fetch("/functions-setShowMarker/" + section);
};

function setVueButtonRowListener() {
	let targets = document.querySelectorAll('.vue-button-row');
	let buttons = [];

	targets.forEach(function (target, i) {
		if (typeof buttons[i] == 'undefined') {
			buttons[i] = target.querySelectorAll('.page-button');
		}
		
		setVueButtonRow(target, buttons[i]);

		window.addEventListener('resize', () => {
			setVueButtonRow(target, buttons[i]);
		});
	});
}

function setVueButtonRow(target, buttons) {
	let rowWidth = 0;

	buttons.forEach((button) => {
		rowWidth += button.offsetWidth;
		rowWidth += 5;
	});

	let lastChild = target.lastElementChild;

	if (rowWidth > (window.innerWidth - 50)) {
		target.classList.add('vue-button-row-js');
		lastChild.classList.remove('vue-button-row-js');

	} else {
		target.classList.remove('vue-button-row-js');
		lastChild.classList.add('vue-button-row-js');
	}
}

function jumpToElement() {
	let id = location.href.split('#')[1];

	if (id != null) {
		let element = document.getElementById(id);
		element.scrollIntoView(false);
	}
};

async function updateAddressMap() {
	let query = document.getElementById('line1').value.replace(',', '');
	query += ' ' + document.getElementById('line2').value.replace(',', '');
	query += ' ' + document.getElementById('line3').value.replace(',', '');
	query += ' ' + document.getElementById('city').value.replace(',', '');
	query += ' ' + document.getElementById('postcode').value.replace(',', '');

	query = encodeURI(query);

	let response = await fetch("https://maps.googleapis.com/maps/api/geocode/json?address=" + query + "&key=AIzaSyAOjxjL_XlAPigkAVGLxqHBSGaUJrMCszg");
  let result = await response.json();
	
	if (result != false) {
		uploadLatLng(result.results[0].geometry.location.lat, result.results[0].geometry.location.lng);
	}
};

async function uploadLatLng(lat, lng) {
	let response = await fetch("/contactUploadLatLng/" + lat + "/" + lng);

	setTimeout(() => {
		document.getElementById('updateAddressMap').submit();
	}, 10);
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