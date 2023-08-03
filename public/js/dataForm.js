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
			event.target.nextElementSibling.innerHTML = event.target.value.split("\\").pop();
		});
	});
};