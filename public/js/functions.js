function setTableMargin() {
	let tables = document.querySelectorAll("table");

	tables.forEach(table => {
		let width = table.querySelector("#" + table.id + " .tr-buttons").offsetWidth;
		let rows = table.querySelectorAll("#" + table.id + " tr");

		if (width == 0) {
			let buttonCount = table.querySelector("#" + table.id + " .tr-buttons").childElementCount;
			width = buttonCount * 35;
		}

		let input = width + "px";

		rows.forEach(row => {
			row.style.paddingRight = input;
		});
	});
};

function showImage(fileName) {
	const imageZone = document.querySelector('.image-viewer');
	const image = document.querySelector('.viewer-image');

	image.src = 'https://hc-main.s3.eu-west-2.amazonaws.com/assets/' + fileName;
	
	imageZone.style.display = 'flex';
};

function closeImage() {
	const imageZone = document.querySelector('.image-viewer');
	const image = document.querySelector('.viewer-image');

	image.src = '';
	
	imageZone.style.display = 'none';
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


// AJAX
function toggleButton(table, ref, column, primaryTable, primaryValue) {
	$.ajax({
		url: "/dataTable-toggleButton/" + table + "/" + column + "/" + primaryTable + "/" + primaryValue,
		type: "GET",
		success: function(result) {
			let button = document.querySelector("#table-" + ref + " #" + column + "-" + primaryValue);

			if (result == 1) {
				button.classList.remove("toggle-false");
				button.classList.remove("fa-circle-xmark");
				
				button.classList.add("toggle-true");
				button.classList.add("fa-circle-check");
			} else {
				button.classList.remove("toggle-true");
				button.classList.remove("fa-circle-check");

				button.classList.add("toggle-false");
				button.classList.add("fa-circle-xmark");
			}
		}
	});
};

function setPrimary(table, ref, column, primaryTable, primaryValue, parent, parentId) {
	$.ajax({
		url: "/dataTable-setPrimary/" + table + "/" + column + "/" + primaryTable + "/" + primaryValue + "/" + parent + "/" + parentId,
		type: "GET",
		success: function(result) {
			let oldPrimarys = document.querySelectorAll("#table-" + ref + " #column-" + column + " .toggle-true");

			oldPrimarys.forEach(oldPrimary => {
				oldPrimary.classList.remove("toggle-true");
				oldPrimary.classList.remove("fa-circle-check");

				oldPrimary.classList.add("toggle-false");
				oldPrimary.classList.add("fa-circle-xmark");
			});

			let button = document.querySelector("#table-" + table + " #" + column + "-" + primaryValue);

			button.classList.remove("toggle-false");
			button.classList.remove("fa-circle-xmark");
			
			button.classList.add("toggle-true");
			button.classList.add("fa-circle-check");
		}
	});
};