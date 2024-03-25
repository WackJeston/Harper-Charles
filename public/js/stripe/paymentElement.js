// details
var amount = 0;
var billingDetails = {};

// settings
var key = null;
var clientSecret = null;
var paymentId = null;
var primaryColour = '#3b4d57';

window.addEventListener("DOMContentLoaded", function() {
	setTimeout(() => {
		stripeRegister();
	}, 2000);
});

function stripeRegister() {
	console.log('stripeRegister');
	console.log(amount);

	// const options = {
	// 	mode: 'payment',
	// 	captureMethod: 'automatic',
	// 	currency: 'gbp',
	// 	amount: amount,
	// 	appearance: {
	// 		theme: 'stripe',
	// 		variables: {
	// 			colorPrimary: primaryColour,
	// 			colorDanger: '#dc3545',
	// 			borderRadius: '6px',
	// 		}
	// 	},
	// };

	// const paymentOptions = {
	// 	layout: {
	// 		type: 'tabs',
	// 		// radios: false,
	// 		// spacedAccordionItems: true
	// 	},
	// 	defaultValues: {
	// 		billingDetails: billingDetails
	// 	},
	// 	paymentMethodOrder: ['card', 'google_pay', 'apple_pay', 'paypal'],
	// };

	// stripe = Stripe(key);
	// stripeElements = stripe.elements(options);
	// stripePaymentElement = stripeElements.create('payment', paymentOptions);
	// stripePaymentElement.mount('#payment-element');
}