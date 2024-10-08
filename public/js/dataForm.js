function setPasswordToggles() {
	inputs = document.querySelectorAll(".password-input");

	inputs.forEach(input => {
		let icon = input.previousSibling.firstChild;

		icon.addEventListener("click", function() {
			if (input.type == "password") {
				input.type = "text";
				icon.classList.remove("fa-eye");
				icon.classList.remove("fa-solid");
				icon.classList.add("fa-eye-slash");
				icon.classList.add("fa-regular");
			} else {
				input.type = "password";
				icon.classList.remove("fa-eye-slash");
				icon.classList.remove("fa-regular");
				icon.classList.add("fa-eye");
				icon.classList.add("fa-solid");
			}
		});
	});
};

function setFileInputs() {
	let inputs = document.querySelectorAll("input.file-input");

	inputs.forEach(input => {
		input.addEventListener("change", function(event) {
			let text = '';

			for (let i = 0; i < event.target.files.length; i++) {
				text += event.target.files[i].name + '<br>';
			}

			event.target.nextElementSibling.innerHTML = text;

			if (event.target.files.length > 1) {
				event.target.parentNode.style.height = (event.target.nextElementSibling.offsetHeight + 10) + 'px';
			}
		});
	});
};