const mix = require('laravel-mix');
const mode = 'admin';
// const mode = 'public';

if (mode == 'public') {
	console.log('--Public--');
	mix.js('resources/js/app.js', 'public/js')
		.sass('resources/sass/app.scss', 'public/css')
		.vue()
		.browserSync(process.env.APP_URL + '.test');
}
else if (mode == 'admin') {
	console.log('--Admin--');
	mix.js('resources/js/app.js', 'public/js')
		.sass('resources/sass/admin.scss', 'public/css')
		.vue()
		.browserSync(process.env.APP_URL + '.test');
}
else {
	console.log('--Failed--');
	mix.js('resources/js/app.js', 'public/js').vue();
}