<template>
  <div id="product-tab-titles">
		<h2>Description</h2>
		<h2>Specifications</h2>
	</div>
	<div id="product-tab-content">
		<div>
			<p>{{ this.product['description'] }}</p>
		</div>
		<ul>
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
  };
</script>
