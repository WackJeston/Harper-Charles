function setIdWidth(repeat = true) {
	let tables = document.querySelectorAll("table");

	if (tables != null) {
		tables.forEach(table => {
			if (table.parentElement.style.display == "none") {
				let mutationObserver = new window.MutationObserver(function() {setIdWidth2(table, mutationObserver)});

				mutationObserver.observe(table.parentElement, {
					attributes: true,
					attributeFilter: ['style']
				});

			} else {
				setIdWidth2(table);

				if (repeat) {
					setTimeout(() => {
						setIdWidth(false);
					}, 500);
			
					setTimeout(() => {
						setIdWidth(false);
					}, 2000);
				}
			}
		});

		if (repeat) {
			let toggle = window.innerWidth < 800 ? true : false;
		
			window.addEventListener('resize', function() {
				if (toggle == true && window.innerWidth > 800) {
					toggle = false;
					setIdWidth();
	
				} else if (toggle == false && window.innerWidth < 800) {
					toggle = true;
					setIdWidth();
				}
			});
		}
	}
}

function setIdWidth2(table, mutationObserver = null) {
	let idColumnWidth = 0;
	let rows = table.querySelectorAll("#" + table.id + " tr:not(tfoot tr)");

	rows.forEach(row => {
		let idColumn = row.querySelector(".column-id span");

		if (idColumn != null) {
			if (idColumn.offsetWidth > idColumnWidth) {
				idColumnWidth = idColumn.offsetWidth;
			}
		}
	});

	idColumnWidth = idColumnWidth + 18;
	
	rows.forEach(row => {
		let idColumn = row.firstElementChild;

		if (idColumn.id == "column-id") {
			idColumn.style.width = idColumnWidth + "px";
			idColumn.style.minWidth = idColumnWidth + "px";
		}
	});

	if (mutationObserver != null) {
		mutationObserver.disconnect();
	}
}

function setTableMargin(repeat = true) {
	let tables = document.querySelectorAll("table");

	if (tables != null) {
		tables.forEach(table => {
			if (repeat && table.parentElement.style.display == "none") {
				let mutationObserver = new window.MutationObserver(function() {setTableMargin2(table, mutationObserver)});

				mutationObserver.observe(table.parentElement, {
					attributes: true,
					attributeFilter: ['style']
				});

			} else {
				setTableMargin2(table);

				if (repeat) {
					setTimeout(() => {
						setTableMargin(false);
					}, 500);
			
					setTimeout(() => {
						setTableMargin(false);
					}, 2000);
				}
			}
		});
	}
};

function setTableMargin2(table, mutationObserver = null) {
	let buttons = table.querySelector("#" + table.id + " .tr-buttons");
	let rows = table.querySelectorAll("#" + table.id + " tr:not(tfoot tr)");

	if (buttons != null) {
		let width = buttons.offsetWidth;

		if (width == 0) {
			let buttonCount = table.querySelector("#" + table.id + " .tr-buttons").childElementCount;
			width = (buttonCount * 35) + 10;
		}

		let input = width + "px";

		rows.forEach(row => {
			row.style.paddingRight = input;
		});
	}

	if (mutationObserver != null) {
		mutationObserver.disconnect();
	}
}

function hideTableColumnsLoop() {
	hideTableColumns();

	window.addEventListener('resize', function() {		
		hideTableColumns();
	});
};

function hideTableColumns() {
	let cells = document.querySelectorAll(".show-mobile-marker");
	let mobileCells = document.querySelectorAll(".hide-mobile-marker");

	if (window.innerWidth < 800) {
		cells.forEach(cell => {
			cell.style.width = cell.getAttribute("data-mobile-width");
		});

		mobileCells.forEach(cell => {
			if (cell.classList.contains("hide-mobile") == false) {
				cell.classList.add("hide-mobile");
			}
		});

	} else {
		cells.forEach(cell => {
			cell.style.width = cell.getAttribute("data-width");
		});

		mobileCells.forEach(cell => {
			if (cell.classList.contains("hide-mobile") == true) {
				cell.classList.remove("hide-mobile");
			}
		});
	}
};

function showImage(url) {
	const imageZone = document.querySelector('.image-viewer');
	const image = document.querySelector('.viewer-image');

	image.src = url;
	
	imageZone.style.display = 'flex';
};

function closeImage() {
	const imageZone = document.querySelector('.image-viewer');
	const image = document.querySelector('.viewer-image');

	image.src = '';
	
	imageZone.style.display = 'none';
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

function selectDropdown(e, table, column, primaryTable, primaryValue) {
	let value = e.target.value;
	
	if (value == null || value == "") {
		value = "null";
	}

	$.ajax({
		url: "/dataTable-selectDropdown/" + table + "/" + column + "/" + primaryTable + "/" + primaryValue + "/" + value,
		type: "GET",
	});
};

function moveSequence(id, direction, ref, tableName, sequenceColumn) {
	$.ajax({
		url: "/dataTable-moveSequence/" + id + "/" + direction + "/" + tableName + "/" + sequenceColumn,
		type: "GET",
		success: function(result) {
			if (result != false) {
				let table = document.querySelector("#table-" + ref);
				let row1 = table.querySelector(`tr[data-record-id="${result[0]}"]`);
				let row2 = table.querySelector(`tr[data-record-id="${result[1]}"]`);

				let temp = row1.innerHTML;
				row1.innerHTML = row2.innerHTML;
				row1.setAttribute("data-record-id", result[1]);
				row2.innerHTML = temp;
				row2.setAttribute("data-record-id", result[0]);
			}
		}
	});
}

function tableRedirect(ref) {
	let url = location.href.split('#')[0];

	location.href = url + '#table-' + ref;
	location.reload();
};

//AJAX - header
function setOrderColumn(e, name, oldName, query, ref) {
	const elements = ["TH", "SPAN"];

	if (oldName != name && elements.includes(e.target.tagName)) {
		$.ajax({
			url: "/dataTable-setOrderColumn/" + name + "/" + query,
			type: "GET",
			success: function() {
				tableRedirect(ref);
			}
		});
	}	
};

function setOrderDirection(direction, query, ref) {
	$.ajax({
		url: "/dataTable-setOrderDirection/" + direction + "/" + query,
		type: "GET",
		success: function() {
			tableRedirect(ref);
		}
	});
};

// AJAX - footer
function changeTableLimit(e, query, oldLimit, ref) {
	let limit = e.target.value;

	if (oldLimit != limit) {
		$.ajax({
			url: "/dataTable-changeLimit/" + limit + "/" + query,
			type: "GET",
			success: function() {
				tableRedirect(ref);
			}
		});
	}	
};

function changeTablePage(query, oldOffset, limit, direction, ref) {
	let offset = direction ? parseInt(oldOffset) + parseInt(limit) : parseInt(oldOffset) - parseInt(limit);

	if (offset < 0) {
		offset = 0;
	}

	$.ajax({
		url: "/dataTable-changePage/" + offset + "/" + query,
		type: "GET",
		success: function() {
			tableRedirect(ref);
		}
	});
};

function resetTableSequence(query, ref) {
	$.ajax({
		url: "/dataTable-resetTableSequence/" + query,
		type: "GET",
		success: function() {
			tableRedirect(ref);
		}
	});
}