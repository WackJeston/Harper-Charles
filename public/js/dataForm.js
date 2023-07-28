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
}

// function setFormValidation() {
// 	let forms = document.querySelectorAll("form");

// 	forms.forEach(form => {
// 		let submit = form.querySelector(".submit");
// 		let inputs = form.querySelectorAll("input");

// 		submit.addEventListener("click", function() {
// 			inputs.forEach(input => {
// 				if (input.required && input.value == "") {
// 					input.classList.add("form-validation-failed");
// 				} else {
// 					input.classList.remove("form-validation-failed");
// 				}
// 			});
// 		});
// 	});
// }