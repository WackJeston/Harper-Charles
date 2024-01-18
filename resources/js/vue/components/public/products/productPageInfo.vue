<template>
  <div id="product-tab-titles">
		<h3 class="tab-selected" data-tab-title="description" @click="this.tabSelect">Description</h3>
		<h3 data-tab-title="specs" @click="this.tabSelect">Specifications</h3>
	</div>
	<div id="product-tab-content">
		<div class="tab-selected" data-tab-content="description">
			<p>{{ this.product['description'] }}</p>
		</div>
		<ul data-tab-content="specs">
			<li v-for="(spec, i) in this.specs"><span>{{ spec.label }}: {{ spec.value }}</span></li>
		</ul>
	</div>
</template>


<script>
  export default {
    props: [
      'product',
      'specs',
    ],

		methods: {
			tabSelect(event) {
				document.querySelectorAll('.tab-selected').forEach(function(element) {
					element.classList.remove('tab-selected');
				});

				event.target.classList.add('tab-selected');
				document.querySelector(`[data-tab-content="${event.target.dataset.tabTitle}"]`).classList.add('tab-selected');
			}
		}
  };
</script>
